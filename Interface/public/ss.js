$.ajax({
    url:'http://localhost:8080/pasiens/21',
    type:'GET',
    success:function(e){
        console.log(e);
    },
    error:function(e){
        // console.error(e);
    }
});

$.ajax({
    url:'http://localhost:8080/ruangans/',
    type:'GET',
    success:function(e){
        console.log(e);
    },
    error:function(e) {
        
    }
    
})