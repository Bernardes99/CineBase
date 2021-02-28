function kickUser(r) {
        
    var i = r.parentNode.parentNode.rowIndex;
    userid=document.getElementById("users_table").rows[i].cells[0].innerHTML;   
    
    if (confirm("Confirme que quer expulsar este utilizador?")) {
        location.replace("kick_user.php?userid="+userid);
    } else {
        //alert("I am an alert box!");
    }    
    
}

function readmitUser(r) {
        
    var i = r.parentNode.parentNode.rowIndex;
    userid=document.getElementById("users_table").rows[i].cells[0].innerHTML;   
    
    if (confirm("Confirme que quer radmitir este utilizador?")) {
        location.replace("readmit_user.php?userid="+userid);
    } else {
        //alert("I am an alert box!");
    }    
    
}

