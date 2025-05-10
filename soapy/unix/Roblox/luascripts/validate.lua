function start(asset,universe)

assetUrl, baseUrl, universeId = "http://unixfr.xyz/asset/?id="..asset,"http://unixfr.xyz",universe
print("Asset URL : " ..assetUrl)
print("Base URL : " ..baseUrl)
print("Universe ID: " ..universe)
pcall(function() game:GetService("ContentProvider"):SetBaseUrl(baseUrl) end)
if universeId ~= nil then
	pcall(function() game:SetUniverseId(universeId) end)
end

local success, errorMessage = pcall(function() 
    game:Load(assetUrl)
end)

if success then
print("validation OK")
    return true
end

return errorMessage
end