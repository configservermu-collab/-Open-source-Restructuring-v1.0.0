#include "AutoCtrl.h"
#include "MainDllVerifier.h"

#include <cstdarg>
#include <cstdio>
#include <cstring>

namespace
{
    constexpr DWORD DOUBLE_CTRL_MS = 300;
    constexpr DWORD PollIntervalMs = 5;

    HANDLE g_workerThread = nullptr;
    HANDLE g_instanceMutex = nullptr;
    volatile LONG g_running = 0;
    bool g_autoCtrlEnabled = false;
    bool g_previousCtrlDown = false;
    DWORD g_lastCtrlDownTick = 0;

    void LogLine(const char* message)
    {
        OutputDebugStringA(message);
    }

    void LogFormatted(const char* format, ...)
    {
        char message[256]{};
        va_list arguments;
        va_start(arguments, format);
        vsprintf_s(message, format, arguments);
        va_end(arguments);
        LogLine(message);
    }

    bool IsThisClientForeground()
    {
        HWND foregroundWindow = GetForegroundWindow();
        if (foregroundWindow == nullptr)
        {
            return false;
        }

        DWORD foregroundProcessId = 0;
        GetWindowThreadProcessId(foregroundWindow, &foregroundProcessId);
        return foregroundProcessId == GetCurrentProcessId();
    }

    void UpdateAutoCtrlPhysicalState()
    {
        if (!IsThisClientForeground())
        {
            g_previousCtrlDown = false;
            g_lastCtrlDownTick = 0;
            return;
        }

        const bool ctrlDown = (GetAsyncKeyState(VK_CONTROL) & 0x8000) != 0;

        if (ctrlDown && !g_previousCtrlDown)
        {
            LogLine("[AUTOCTRL] Ctrl DOWN\n");
            const DWORD now = GetTickCount();
            if (g_lastCtrlDownTick != 0)
            {
                const DWORD delta = now - g_lastCtrlDownTick;
                LogFormatted("[AUTOCTRL] second Ctrl delta=%lu\n", delta);
                if (delta <= DOUBLE_CTRL_MS)
                {
                        g_autoCtrlEnabled = !g_autoCtrlEnabled;
                        LogLine(g_autoCtrlEnabled ? "[AUTOCTRL] toggled ON\n" : "[AUTOCTRL] toggled OFF\n");
                    g_lastCtrlDownTick = 0;
                }
                else
                {
                    g_lastCtrlDownTick = now;
                    LogFormatted("[AUTOCTRL] first Ctrl tick=%lu\n", now);
                }
            }
            else
            {
                g_lastCtrlDownTick = now;
                LogFormatted("[AUTOCTRL] first Ctrl tick=%lu\n", now);
            }
        }
        else if (!ctrlDown && g_previousCtrlDown)
        {
            LogLine("[AUTOCTRL] Ctrl UP\n");
        }

        g_previousCtrlDown = ctrlDown;
    }

    DWORD WINAPI AutoCtrlWorker(LPVOID)
    {
        LogLine("[AUTOCTRL] worker started\n");
        while (InterlockedCompareExchange(&g_running, 0, 0) != 0)
        {
            UpdateAutoCtrlPhysicalState();
            Sleep(PollIntervalMs);
        }

        LogLine("[AUTOCTRL] worker stopped\n");
        return 0;
    }

    bool InitializeAutoCtrl()
    {
        if (!VerifyLoadedMainDllSha256())
        {
            LogLine("[AUTOCTRL][ERROR] MAIN_DLL_MISMATCH\n");
            return false;
        }

        g_autoCtrlEnabled = false;
        LogLine("[AUTOCTRL] initial state=OFF\n");
        g_previousCtrlDown = (GetAsyncKeyState(VK_CONTROL) & 0x8000) != 0;
        return true;
    }
}

extern "C" __declspec(dllexport) void EntryProc()
{
    static volatile LONG initialized = 0;
    if (InterlockedCompareExchange(&initialized, 1, 0) != 0)
    {
        return;
    }

    LogLine("[AUTOCTRL] initialized\n");

    char mutexName[64]{};
    sprintf_s(mutexName, "Local\\AutoCtrl-%lu", GetCurrentProcessId());
    g_instanceMutex = CreateMutexA(nullptr, TRUE, mutexName);
    if (g_instanceMutex == nullptr)
    {
        LogFormatted("[AUTOCTRL][ERROR] instance mutex failed error=%lu\n", GetLastError());
        return;
    }

    if (GetLastError() == ERROR_ALREADY_EXISTS)
    {
        CloseHandle(g_instanceMutex);
        g_instanceMutex = nullptr;
        LogLine("[AUTOCTRL][ERROR] duplicate instance ignored\n");
        return;
    }

    if (!InitializeAutoCtrl())
    {
        return;
    }

    InterlockedExchange(&g_running, 1);
    g_workerThread = CreateThread(nullptr, 0, AutoCtrlWorker, nullptr, 0, nullptr);
    if (g_workerThread == nullptr)
    {
        InterlockedExchange(&g_running, 0);
        LogFormatted("[AUTOCTRL][ERROR] worker creation failed error=%lu\n", GetLastError());
        return;
    }

}

extern "C" __declspec(dllexport) void ShutdownAutoCtrl()
{
    InterlockedExchange(&g_running, 0);
    if (g_workerThread != nullptr)
    {
        WaitForSingleObject(g_workerThread, INFINITE);
        CloseHandle(g_workerThread);
        g_workerThread = nullptr;
    }

    if (g_instanceMutex != nullptr)
    {
        ReleaseMutex(g_instanceMutex);
        CloseHandle(g_instanceMutex);
        g_instanceMutex = nullptr;
    }

}

BOOL WINAPI DllMain(HMODULE module, DWORD reason, LPVOID)
{
    if (reason == DLL_PROCESS_ATTACH)
    {
        DisableThreadLibraryCalls(module);
    }
    else if (reason == DLL_PROCESS_DETACH)
    {
        InterlockedExchange(&g_running, 0);
    }

    return TRUE;
}
