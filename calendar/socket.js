let socket = new WebSocket("ws://localhost:3000/");

socket.onopen = (e) => {
    console.log("Conection Extablished from Client");
}

socket.onmessage = (e) => {
   
     swal.fire({
        title: "Update",
        text: "Update Has been Made, Please Reload the scheduler to see changes",
        icon: "success"
    }).then(function () {
        location.reload();
    }); 
}

