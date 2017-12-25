/*
	Blocking 정의 함수
	@Author Henry Kim
	@Date 2017-09-10
*/

var varTIME1, varTIME2;

/*
	안드로이드에서 호출되는 변수 & 함수
*/
var androidBridge = function (posX, posY) {

	var point = document.getElementById("point");
	var loading = document.getElementById("loading");

	// #1. 입력 좌표 보정 (이미지 축소에 따른 작업)
 	posX -= 195;
 	posY -= 143;

	// #2-1. 좌표 상의 pixel 정보를 얻기 위한 변수 선언
	var canvas = document.createElement('canvas');
	var ctx = canvas.getContext('2d');
	var img = document.getElementById('map_area');

	// #2-2. 로드 된 이미지를 변수에 저장
	canvas.width = img.width;
	canvas.height = img.height;
	ctx.drawImage(img, 0, 0);

	// #2-3. 이미지의 특정 픽셀의 컬러 정보 (R,G,B,A) 값을 가져오기
	var pixel = ctx.getImageData(posX, posY, 1, 1).data;

	// #3   . 지도 상 유효한 지역은 White (255,255,255,255) 이기 때문에 이를 기준으로 판단
	//      - 좌표가 유효한 경우 true를 반환하고 해당 포인트를 표시 함
	//      - 좌표가 유효한 경우 false를 반환하고 해당 포인트를 표시 안함
	if(pixel[0] == 255 && pixel[1] == 255 && pixel[2] == 255 && pixel[3] == 255) {
		document.getElementById('prev_posX').value = posX;
		document.getElementById('prev_posY').value = posY;
	}
	else {
		posX = document.getElementById('prev_posX').value;
		posY = document.getElementById('prev_posY').value;
	}

	if(posX == 0 && posY == 0) {
		loading.style.display = 'block';
	}
	else {
		calcPosition(ctx, posX, posY);
		point.style.display = 'block';
		loading.style.display = 'none';
	}
}



/*
	좌표 계산 함수
*/
function calcPosition(ctx, posX, posY) {

	var point = document.getElementById("point");

	var adj_posX = posX;
	var adj_posY = posY;
	var pixel_Move;
	var varPixel, isDone = false;

	// #1. 이미지 크기에 따른 위치 보정
	//     (추정된 사용자의 위치가 유효한 위치 인 경우 현재 위치를 나타내는 그림의 크기를 고려하여 좌표 보정)
	while(!isDone) {

		// #1-1. 좌표 X,Y를 1씩 보정
		adj_posX -= 1;
		adj_posY -= 1;

		// #1-2. 그림 크기(20x20) 만큼의 크기에서 금지 지역이 있는 지 확인하기 위한 데이터 저장
		pixel_Move = ctx.getImageData(adj_posX, adj_posY, 20, 20).data;

		// #1-3. 그림 크기 지역에서 white 값이 아닌 지 loop
		for(i=0; i<pixel_Move.length; i++) {
			if(pixel_Move[i] != 255) {
				break;
			}
		}

		// #1-4. 만약 20x20만큼 white space 가 있다면 위 loop를 모두 체크 했을 것임.
		if(i == pixel_Move.length) {
			point.style.left = adj_posX + 'px';
			point.style.top = adj_posY + 'px';

			isDone = true;
		}

	} // while(!isDone)

}


// 기능 테스트 함수
function testFunc() {

	var prev_posX = document.getElementById('prev_posX').value;
	var prev_posY = document.getElementById('prev_posY').value;

	console.log(prev_posX, prev_posY);

	var posX = document.getElementById('posX').value;
	var posY = document.getElementById('posY').value;

	var point = document.getElementById('check_point');
	point.style.left = (posX - 195) + 'px';
	point.style.top = (posY - 143) + 'px';
	point.style.display = 'block';

	var x = androidBridge(posX, posY);
}

/*
	탑승 중 상황일 때 움직이는 화면 구성 함수 (UP 방향)
*/
function setFunc_UP() {

	var el_icon = document.getElementById('el_icon');
	el_icon.style.display = 'none';

	document.getElementById('up_posY').value = 480;
	varTIME1 = setInterval(moveFunc_UP, 700);
}

/*
	EL movement function - UP
*/
function moveFunc_UP() {

	var el_icon = document.getElementById('el_icon');
	var posY = document.getElementById('up_posY').value;
	var offset = 20;

	console.log(posY);

	el_icon.style.left = 290 + 'px';
	el_icon.style.top = posY + 'px';
	el_icon.style.display = 'block';

	if( (posY - offset) < 50) {
		document.getElementById('up_posY').value = 480;
	}
	else {
		document.getElementById('up_posY').value = (posY - offset);
	}

}


/*
	탑승 중 상황일 때 움직이는 화면 구성 함수 (DN 방향)
*/
function setFunc_DN() {

	var el_icon = document.getElementById('el_icon');
	el_icon.style.display = 'none';


	document.getElementById('dn_posY').value = 50;
	varTIME2 = setInterval(moveFunc_DN, 700);
}


/*
	EL movement function - DN
*/
function moveFunc_DN() {

	var el_icon = document.getElementById('el_icon');
	var posY = document.getElementById('dn_posY').value;
	var offset = -20;

	console.log(posY);

	el_icon.style.left = 290 + 'px';
	el_icon.style.top = posY + 'px';
	el_icon.style.display = 'block';

	if( (posY - offset) > 480) {
		document.getElementById('dn_posY').value = 50;
	}
	else {
		document.getElementById('dn_posY').value = (posY - offset);
	}
}


function clearFunc() {
	window.clearInterval(varTIME1);
	window.clearInterval(varTIME2);
}
