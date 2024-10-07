

	
function paddedFormat(num) {
    return num < 10 ? "0" + num : num; 
}

function startCountDown(duration, element) {

    let secondsRemaining = duration;
    let min = 0;
    let sec = 0;

    var countInterval = setInterval(function () {

        min = parseInt(secondsRemaining / 60);
        sec = parseInt(secondsRemaining % 60);

        element.textContent = `${paddedFormat(min)}:${paddedFormat(sec)}`;

        secondsRemaining = secondsRemaining - 1;
		
        if (secondsRemaining < 0) { 

					
				clearInterval(countInterval)
				//actualizar();
				//reiniciar();
			};

    }, 1000);
	
	$('#hdnval').val(countInterval);
}

 function reiniciar(){
	var time_minutes = 5; // Value in minutes
    var time_seconds = 0; // Value in seconds

    var duration = time_minutes * 60 + time_seconds;

    element = document.querySelector('#count-down-timer');
    element.textContent = `${paddedFormat(time_minutes)}:${paddedFormat(time_seconds)}`;

    startCountDown(--duration, element);
}


window.onload =  function () {
    var time_minutes = 5; // Value in minutes
    var time_seconds = 0; // Value in seconds

    var duration = time_minutes * 60 + time_seconds;

    element = document.querySelector('#count-down-timer');
    element.textContent = `${paddedFormat(time_minutes)}:${paddedFormat(time_seconds)}`;

    startCountDown(--duration, element);


};




