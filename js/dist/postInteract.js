function likePost(t){$.post("functions/postInteract.php",{p:t,a:"like"}).done(function(t){try{var o=JSON.parse(t)}catch(n){alert(n)}0==o.type?$("#postContainer").html(o.text):loadPosts()})}function unLikePost(t){$.post("functions/postInteract.php",{p:t,a:"unlike"}).done(function(t){try{var o=JSON.parse(t)}catch(n){alert(n)}0==o.type?$("#postContainer").html(o.text):loadPosts()})}$(document).ready(function(){$("#postLike").click(function(){console.log("log")})});