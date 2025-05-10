
function start(playerid,baseurl)
	baseUrl, characterAppearanceUrl, fileExtension, x, y = "http://unixfr.xyz/", "http://unixfr.xyz/v1.1/avatar-fetch/?userId="..playerid, "obj", 840, 840
	
	pcall(function() game:GetService("ContentProvider"):SetBaseUrl(baseUrl) end)
	game:GetService("ScriptContext").ScriptsDisabled = true 
	
	local player = game:GetService("Players"):CreateLocalPlayer(0)
	player.CharacterAppearance = characterAppearanceUrl
	player:LoadCharacterBlocking()
	
	-- Raise up the character's arm if they have gear.
	if player.Character then
		if player.Character:FindFirstChildOfClass("Tool") then
			player.Character.Torso["Right Shoulder"].CurrentAngle = math.rad(90)
		end
	end
	
	return game:GetService("ThumbnailGenerator"):Click("OBJ", x, y, --[[hideSky = ]] true)
	end