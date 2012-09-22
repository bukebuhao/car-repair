$(function(){
	
	//添加自定义函数
	jQuery.validator.addMethod(
	"telrequired",
	function(value, element,praram) {
		var telephone = $("input[name='telephone']").val();
		var mobile_tel = $("input[name='mobile_tel']").val();
		if ($.trim(telephone).length > 0 || $.trim(mobile_tel).length > 0) {
			return true;
		}
		return false;
	},
	"手机和电话至少一个不为空");
	
	

  jQuery.validator.addMethod(
	"isPhone",
	function(value, element,praram) {
		if ($.trim(value).length == 0) {
			return true;
		}
		var mobile = /^((1[1-9]{2})+\d{8})$/;
		var tel = /^\d{3,4}-?\d{7,9}$/;
		return (tel.test(value) || mobile.test(value));
	},
	"请正确填写您的联系电话");
	
	
    //添加验证
	$("#leavewordForm").validate({
		rules: {
			title:  {
				required: true,
				maxlength: 20
			},
			content: {
					required: true,
					minlength: 5,
					maxlength: 200
			},
			username: {
				required: true,
				minlength: 2
			},
			telephone: {
				telrequired: true,
				isPhone:true
			},
			mobile_tel: {
				telrequired: true,
				isPhone:true
			},
			email: {
				email: true
			}
			
		}
	});
	
	// 提交留言
	$("#leavewordSumbit").click(function() {
		$("#leavewordForm").submit();
		
	});
	
	
});