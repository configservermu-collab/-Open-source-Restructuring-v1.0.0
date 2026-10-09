#include "CryptoUtils.h"

#include <bcrypt.h>
#include <wincrypt.h>

#include <fstream>
#include <iomanip>
#include <sstream>

#pragma comment(lib, "bcrypt.lib")
#pragma comment(lib, "crypt32.lib")

namespace
{
    bool HashBytes(LPCWSTR algorithm, const BYTE* data, size_t size, std::vector<BYTE>& hash)
    {
        BCRYPT_ALG_HANDLE alg = nullptr;
        BCRYPT_HASH_HANDLE h = nullptr;
        DWORD objectLength = 0, cb = 0, hashLength = 0;
        std::vector<BYTE> object;
        bool ok = false;
        if (BCryptOpenAlgorithmProvider(&alg, algorithm, nullptr, 0) != 0) goto cleanup;
        if (BCryptGetProperty(alg, BCRYPT_OBJECT_LENGTH, reinterpret_cast<PUCHAR>(&objectLength), sizeof(objectLength), &cb, 0) != 0) goto cleanup;
        if (BCryptGetProperty(alg, BCRYPT_HASH_LENGTH, reinterpret_cast<PUCHAR>(&hashLength), sizeof(hashLength), &cb, 0) != 0) goto cleanup;
        object.resize(objectLength);
        hash.resize(hashLength);
        if (BCryptCreateHash(alg, &h, object.data(), objectLength, nullptr, 0, 0) != 0) goto cleanup;
        if (BCryptHashData(h, const_cast<PUCHAR>(data), static_cast<ULONG>(size), 0) != 0) goto cleanup;
        if (BCryptFinishHash(h, hash.data(), hashLength, 0) != 0) goto cleanup;
        ok = true;
    cleanup:
        if (h) BCryptDestroyHash(h);
        if (alg) BCryptCloseAlgorithmProvider(alg, 0);
        return ok;
    }
}

std::string BytesToHex(const BYTE* data, size_t size)
{
    std::ostringstream out;
    for (size_t i = 0; i < size; ++i)
        out << std::hex << std::setw(2) << std::setfill('0') << static_cast<unsigned>(data[i]);
    return out.str();
}

std::string Sha256Hex(const std::string& data)
{
    std::vector<BYTE> hash;
    if (!HashBytes(BCRYPT_SHA256_ALGORITHM, reinterpret_cast<const BYTE*>(data.data()), data.size(), hash)) return {};
    return BytesToHex(hash.data(), hash.size());
}

bool Sha256FileHex(const std::string& path, std::string& hashHex)
{
    std::ifstream file(path, std::ios::binary);
    if (!file) return false;
    std::ostringstream buffer;
    buffer << file.rdbuf();
    hashHex = Sha256Hex(buffer.str());
    return !hashHex.empty();
}

bool Sha512Bytes(const BYTE* data, size_t size, BYTE out[64])
{
    std::vector<BYTE> hash;
    if (!HashBytes(BCRYPT_SHA512_ALGORITHM, data, size, hash) || hash.size() != 64) return false;
    memcpy(out, hash.data(), 64);
    return true;
}

bool Base64Decode(const std::string& input, std::vector<BYTE>& output)
{
    DWORD size = 0;
    if (!CryptStringToBinaryA(input.c_str(), 0, CRYPT_STRING_BASE64, nullptr, &size, nullptr, nullptr)) return false;
    output.resize(size);
    return CryptStringToBinaryA(input.c_str(), 0, CRYPT_STRING_BASE64, output.data(), &size, nullptr, nullptr) != FALSE;
}

std::string RandomHex(size_t bytes)
{
    std::vector<BYTE> data(bytes);
    if (BCryptGenRandom(nullptr, data.data(), static_cast<ULONG>(data.size()), BCRYPT_USE_SYSTEM_PREFERRED_RNG) != 0) return {};
    return BytesToHex(data.data(), data.size());
}

std::string EscapeJson(const std::string& value)
{
    std::string out;
    for (char c : value)
    {
        switch (c)
        {
        case '\\': out += "\\\\"; break;
        case '"': out += "\\\""; break;
        case '\n': out += "\\n"; break;
        case '\r': out += "\\r"; break;
        case '\t': out += "\\t"; break;
        default: out.push_back(c); break;
        }
    }
    return out;
}
