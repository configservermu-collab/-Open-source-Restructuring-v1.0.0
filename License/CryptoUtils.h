#pragma once

#include <Windows.h>
#include <string>
#include <vector>

std::string Sha256Hex(const std::string& data);
bool Sha256FileHex(const std::string& path, std::string& hashHex);
bool Sha512Bytes(const BYTE* data, size_t size, BYTE out[64]);
std::string BytesToHex(const BYTE* data, size_t size);
bool Base64Decode(const std::string& input, std::vector<BYTE>& output);
std::string RandomHex(size_t bytes);
std::string EscapeJson(const std::string& value);
