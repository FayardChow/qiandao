var myVideob1 = document.getElementById("b1");
function playVid() {
	myVideob1.play();
}
var lottery = {
	click: 0,
	index: 1, //当前转动到哪个位置，起点位置
	count: 0, //总共有多少个位置
	timer: 0, //setTimeout的ID，用clearTimeout清除
	speed: 100, //初始转动速度
	times: 0, //转动次数
	cycle: 14, //转动基本次数：即至少需要转动多少次再进入抽奖环节
	prize: 1, //中奖位置
	init: function(id) {
		if($("#" + id).find(".lottery-unit").length > 0) {
			$lottery = $("#" + id);
			$units = $lottery.find(".lottery-unit");
			this.obj = $lottery;
			this.count = $units.length;
			$lottery.find(".lottery-unit-" + this.index).addClass("active");
		}
	},
	roll: function() {
		var index = this.index;
		var count = this.count;
		var lottery = this.obj;
		$(lottery).find(".lottery-unit-" + index).removeClass("active");
		index += 1;
		if(index > count - 1) {
			index = 0;
		}
		$(lottery).find(".lottery-unit-" + index).addClass("active");
		this.index = index;
		return false;
	}
};

function roll(callback) {
	lottery.times += 1;
	lottery.roll();
	var prize_site = $("#lottery").attr("prize_site");
	
	if(lottery.times > lottery.cycle + 4 && lottery.index == lottery.prize) {
		
		setTimeout(function(){
			callback();
		
			clearTimeout(lottery.timer);
			lottery.prize = 1;
			lottery.times = 0;
			lottery.speed = 100;
			lottery.click = 0;
				
		}, 1000)
	} else {
		if(lottery.times < lottery.cycle) {
			lottery.speed += 10;
		}else{
			if( lottery.times > lottery.cycle + 2 && (lottery.prize == lottery.index + 1 || lottery.prize == lottery.index + 2)) {
				lottery.speed += 100;
			} else {
				lottery.speed += 20;
			}
		}
		lottery.timer = setTimeout(function(){
			roll(callback);
		}, lottery.speed);
	}
	return false;
}

$(function() {
	lottery.init('lottery');
	
	var tmp_cishu = getCookie('times') || 0;
	if( tmp_cishu > 2 ) tmp_cishu = 2;
	$('#J_bm_total').html(2 - tmp_cishu);
	$('#J_bm_buzhong b').html(2 - tmp_cishu);
	if( getCookie('bm_must') != 'yes' ){
		$('#J_bm_total').html( 0 );
	}
	
	$('#J_bm_goon').click(function(){
		$("#lottery a").click();
		$('#J_bm_buzhong').hide();
	})




	
	function choujiangcishu(){
        if (!getCookie('times')) {
            setCookie('times', 1)
        } else {
            var nowcookie = getCookie('times');
            var newcookie = nowcookie - 1 + 2;
            setCookie('times', newcookie);
        }
    }
	
	function buzhong(){
       lottery.prize = 1;
    }
	function zhong(){
		var prize = GetRandomNum(2, lottery.count - 1);
		lottery.prize = prize;
    }
	
	//抽奖按钮
	$("#lottery a").click(function() {
		if( lottery.click ){
			return !1;
		}
		
		choujiangcishu();
		var now_times=getCookie('times');

		//2次抽奖机会
		if(now_times<2){
			buzhong();
		}else{
			zhong();
		}
		
		playVid()
		lottery.click = 1;
		
		$('#J_bm_total').html($('#J_bm_total').html()-1);
		$('#J_bm_buzhong b').html($('#J_bm_buzhong b').html()-1);
		
		roll(function(){
			if( lottery.prize == 1 ){
				$('#J_bm_buzhong').show();
			}else{
				var tmp = bmzhongjian[lottery.prize - 1];
				setCookie("bmdj_dj", tmp.dj);
				setCookie("bmdj_title", tmp.title);
				setCookie("bmdj_img", tmp.img);
				setCookie("bmdj_img1", tmp.img1);
				setCookie("bmdj_tu", tmp.tu);
				setCookie("bmdj_content", tmp.content);
				
				//var obj = {"isHasChance":"true","rotate":tmp.rotate,"results":"恭喜你抽中【"+ tmp.title +"】,必须分享到空间后,才可以领取!分享后重新打开网站即可领取"};
				
		
		          fxtitle.innerHTML=tmp.title;
				  fximage.src=tmp.img1;
				
				
				//$('#J_bm_zhong span').html( tmp.title );
				//$('#J_bm_zhong .bm-zhong-info-img1').append('<img src="'+ tmp.img1 +'" />');
				$('#J_bm_zhong').show();
				

				
			}
		});
		
	});
})











