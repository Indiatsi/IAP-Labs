var allowedTypes = ['image/png','image/jpeg']
var maxFileSize = 51200

$(()=>{
	$('table#data').DataTable()
	$('input')[0].focus()
	$('.toast').toast('show')
	$(".custom-file-input").on("change", function() {
	  var fileName = $(this).val().split("\\").pop();
	  if(fileName)
	  	$(this).siblings(".custom-file-label").addClass("selected").html(fileName)
	  else
	  	$(this).siblings(".custom-file-label").removeClass("selected").html($(this).data('original'))
	});
})

function changePhoto(id,src){
	$("#"+id).find('img').attr('src',src)
}

//Input Validation Scripts (Front-End Validation)
function validateForm(){
	var first_name = $('input[name=first_name]').val().trim()
	var last_name = $('input[name=last_name]').val().trim()
	var city_name = $('input[name=city_name').val().trim()
	var username = $('input[name=username').val().trim()
	var password = $('input[name=password').val()
	var image = $('input[name=fileToUpload]')[0].files
	var data = [first_name,last_name,city_name,username]
	for(index in data){
		if(data[index].trim().length<1){
			$('#info').html('<div class="toast" data-delay="3000"><div class="toast-body bg-danger text-light"><strong>Error: </strong>Ensure You Have Filled all Fields!</div></div>').find('.toast').toast('show')
			return false
		}
		if(/[^a-z0-9 \'-]/i.test(data[index])){
			$('#info').html('<div class="toast" data-delay="3000"><div class="toast-body bg-danger text-light"><strong>Error: </strong>Only Alphanumeric Characters are Required!</div></div>').find('.toast').toast('show')
			return false
		}
	}
	if(password.length<8){
		$('#info').html('<div class="toast" data-delay="3000"><div class="toast-body bg-danger text-light"><strong>Error: </strong>Password must be Atleast 8 Characters Long!</div></div>').find('.toast').toast('show')
		return false
	}
	if(!/[a-z]/.test(password) || !/[A-Z]/.test(password) || !/[0-9]/.test(password)){
		$('#info').html('<div class="toast" data-delay="3000"><div class="toast-body bg-danger text-light"><strong>Error: </strong>Password must contain Lowercase, Uppercase and Numeric Characters</div></div>').find('.toast').toast('show')
		return false
	}
	if(image.length<0){
		$('#info').html('<div class="toast" data-delay="3000"><div class="toast-body bg-danger text-light"><strong>Error: </strong>Please Select a Profile Picture</div></div>').find('.toast').toast('show')
		return false
	}
	if(allowedTypes.indexOf(image[0].type)===-1){
		$('#info').html('<div class="toast" data-delay="3000"><div class="toast-body bg-danger text-light"><strong>Error: </strong>Image is not of Correct Type</div></div>').find('.toast').toast('show')
		return false
	}
	if(image[0].size>maxFileSize){
		$('#info').html('<div class="toast" data-delay="3000"><div class="toast-body bg-danger text-light"><strong>Error: </strong>Image Size too Large! (>50kb)</div></div>').find('.toast').toast('show')
		return false
	}
	return true;
}

function validateLogin(){
	var username = $('input[name=username').val().trim()
	var password = $('input[name=password').val()
	var data = [username,password]
	for(index in data){
		if(data[index].trim().length<1){
			$('#info').html('<div class="toast" data-delay="3000"><div class="toast-body bg-danger text-light"><strong>Error: </strong>Ensure You Have Filled all Fields!</div></div>').find('.toast').toast('show')
			return false
		}
	}
	return true
}
//End of Validation Scripts

//Password view script
function viewPassword(span,input){
	$input = $(input).attr('type')
	if($input.localeCompare('password') === 0){
		$(input).attr('type','text')
		$(span+">i").removeClass('fa-eye').addClass('fa-eye-slash')
	}else{
		$(input).attr('type','password')
		$(span+">i").removeClass('fa-eye-slash').addClass('fa-eye')
	}
	$(input).focus()
	return false
}
//End of Password View Script