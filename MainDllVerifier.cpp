#include "MainDllVerifier.h"

#include "CustomerBinding.h"
#include "License/CryptoUtils.h"

#include <Windows.h>

bool GetLoadedMainDllSha256(std::string& hash)
{
    hash.clear();
    const HMODULE mainModule = GetModuleHandleA("Main.dll");
    if (mainModule == nullptr)
    {
        return false;
    }

    char path[MAX_PATH] = {};
    const DWORD length = GetModuleFileNameA(mainModule, path, MAX_PATH);
    return length > 0 && length < MAX_PATH && Sha256FileHex(path, hash);
}

bool VerifyLoadedMainDllSha256()
{
    std::string hash;
    return GetLoadedMainDllSha256(hash) && _stricmp(hash.c_str(), CustomerBinding::MainDllSha256) == 0;
}
