function loadPosts(){$.post("functions/getPosts.php",{page:currentPage,posts:10,all:loadAll}).done(function(t){try{var n=JSON.parse(t)}catch(o){alert(o)}0==n.type?$("#postContainer").html(n.text):$("#postContainer").html(n.text)})}var currentPage=0,loadAll=!1;$(document).ready(function(){$("#postContainer").html("Loading posts..."),loadPosts(),setInterval(loadPosts,2500),$("#allPostCheck").click(function(t){loadAll=$("#allPostCheck").prop("checked"),$("#postContainer").html("Loading posts..."),loadPosts()}),$("#nextBtnLink").click(function(t){t.preventDefault(),currentPage+=1,loadPosts(),currentPage>0&&$("#backBtn").show()}),$("#backBtnLink").click(function(t){t.preventDefault(),currentPage-1>=0&&(currentPage-=1),loadPosts(),0==currentPage&&$("#backBtn").hide()})});