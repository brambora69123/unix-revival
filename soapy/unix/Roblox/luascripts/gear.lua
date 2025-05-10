function start(assetid,baseurl)

assetUrl, fileExtension, x, y, baseUrl = "http://unixfr.xyz/asset/?id="..assetid,"png",500,500,"http://unixfr.xyz/"

pcall(function() game:GetService("ContentProvider"):SetBaseUrl(baseUrl) end)
game:GetService("ScriptContext").ScriptsDisabled = true

for _, object in pairs(game:GetObjects(assetUrl)) do
	object.Parent = workspace
end

return game:GetService("ThumbnailGenerator"):Click(fileExtension, x, y, --[[hideSky = ]] true, --[[crop =]] true)
end

