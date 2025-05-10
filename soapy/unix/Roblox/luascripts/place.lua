-- Place v1.0.2a
function start(assetid,baseurl)
assetUrl, fileExtension, x, y, baseUrl, universeId = "http://unixfr.xyz/asset/?id="..assetid,"png",500,500,"http://unixfr.xyz/", assetid

pcall(function() game:GetService("ContentProvider"):SetBaseUrl(baseUrl) end)
if universeId ~= nil then
	pcall(function() game:SetUniverseId(universeId) end)
end

game:Load(assetUrl)

game:GetService("ScriptContext").ScriptsDisabled = true
game:GetService("StarterGui").ShowDevelopmentGui = false

return game:GetService("ThumbnailGenerator"):Click(fileExtension, x, y, --[[hideSky = ]] false)
end
