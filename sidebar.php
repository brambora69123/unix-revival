
    <div class="side-bar" id="side-bar">
        <ul>
            <li style="display: flex; margin-bottom: 10px;">
                
                    <div class="smaller-user-card" style="">
                        <a href="/viewuser?id=<?php echo $id; ?>" style="height: 30px;"><img src="./renders/<?php echo $id; ?>-closeup.png" alt="" width="30px"></a>
                    </div>
                    
                    <p class="side-bar-name">
                        <a class="side-bar-name" href="/viewuser?id=<?php echo $id; ?>"><?php echo $name; ?></a>
                    </p>
                
            </li>
            <hr style="margin-right: 10px;">
            <li><a href="/viewuser?id=<?php echo $id; ?>">Profile</a></li>
            <li><a href="/friends">Friends</a></li>
            <li><a href="/players">Users</a></li>
            <li><a href="/downloadclient">Download</a></li>
            <li><a href="/create">Create</a></li>
        </ul>
    </div>

    <button class="side-bar-button" id="side-bar-button"><img src="./media/images/unlocked.png" alt="" width="20px"></button>
    <!--<div class="side-bar-hover-div" id="hover"></div>-->

    <script>
        const button = document.getElementById("side-bar-button");
        const sidebar = document.getElementById("side-bar");
        var open = false;
        var locked = JSON.parse(getCookie("sidebarlocked")) ?? false;

        if (locked == true){
            open = true;
            sidebar.style.left = "10px"
            button.style.left = "165px"
            button.innerHTML = "<img src='./media/images/locked.png' width='20px'>";
        }

        function getCookie(cname) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for(let i = 0; i <ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
                }
            }
            return null;
        }

        button.addEventListener("click", () => {
            if (locked == true){
                button.innerHTML = "<img src='./media/images/unlocked.png' width='20px'>";
                document.cookie = "sidebarlocked=false";
                locked = false;
            } else if (locked == false) {
                button.innerHTML = "<img src='./media/images/locked.png' width='20px'>";
                document.cookie = "sidebarlocked=true";
                locked = true;
            }
        });

        document.addEventListener("mousemove", (event) => {
            if (event.clientX <= 200 && event.clientY >= 100){

                if (open == false) {
                    gsap.to(sidebar, {
                    duration: 0.5,
                    left: "10px",
                    ease: "circ.out",
                    });

                    gsap.to(button, {
                    duration: 0.4,
                    left: "165px",
                    ease: "circ.out",
                    });

                }

                open = true;

            } else{

                if (open == true && locked == false) {
                    gsap.to(sidebar, {
                    duration: 0.5,
                    left: "-210px",
                    ease: "circ.out",
                    });
  
                    gsap.to(button, {
                    duration: 0.4,
                    left: "10px",
                    ease: "circ.out",
                    });

                }

                open = false;

            }
        });

    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.0/gsap.min.js"></script>

