#pragma once

#include <string>

bool GetLoadedMainDllSha256(std::string& hash);
bool VerifyLoadedMainDllSha256();
