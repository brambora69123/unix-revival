

function start(assetid,baseurl)

-- Decal v1.0.2
-- Used for faces and decals

assetUrl, fileExtension, x, y, baseUrl = "http://unixfr.xyz/asset/?id="..assetid,"png",1680,1680,"http://unixfr.xyz/"

pcall(function() game:GetService("ContentProvider"):SetBaseUrl(baseUrl) end) 
game:GetService('ScriptContext').ScriptsDisabled = true

local decal = game:GetObjects(assetUrl)[1]

local thumbnailGenerator = game:GetService("ThumbnailGenerator")

local image, requestedUrls
local success = pcall(function()
	image, requestedUrls = thumbnailGenerator:ClickTexture(decal.Graphic, fileExtension, x, y)
end)

if success then
	return image, requestedUrls
end

-- if we fail return the hourglass, since we're probably in moderation.
return "/9j/4AAQSkZJRgABAQEASABIAAD//gATQ3JlYXRlZCB3aXRoIEdJTVD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wgARCAABAAEDAREAAhEBAxEB/8QAFAABAAAAAAAAAAAAAAAAAAAACf/EABQBAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhADEAAAAX8P/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABBQJ//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPwF//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPwF//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQAGPwJ//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPyF//9oADAMBAAIAAwAAABAf/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPxB//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPxB//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxB//9k=", {decal.Texture}
end

