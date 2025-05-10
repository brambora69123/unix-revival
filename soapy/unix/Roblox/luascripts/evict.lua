function start(elplayer, baseurl)
function kickPlayerByUsername(playerName)
    local player = game.Players:FindFirstChild(playerName)

    if player then
        player:Kick("You have been banned from UNIX.")
    else
        print("Player not found.")
    end
end

local playerNameToKick = elplayer
kickPlayerByUsername(playerNameToKick)
end
