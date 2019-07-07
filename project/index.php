<?php
require_once "functions.php";
?>

<!DOCTYPE HTML>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>

function addContact(){

	var editFieldsOpen = $('input.edit').get().length;

	if(editFieldsOpen){
		alert('Please save your current edits before adding a new contact.');
	}
	else{
		var name = $('#name').val();
		var phone = $('#phone').val();
		var email = $('#email').val();
		
		$.post('functions.php',
		   {do: 'addContact',
		    name: name,
		    phone: phone,
		    email: email},
				function(data){
					if(data == 'fail'){
						alert('Something went wrong. Contact not saved');
					}
					else{
						$('#name').val('');
						$('#phone').val('');
						$('#email').val('');
						location.reload(); 
					}
				});
	}
}

function editContact(id){

	var nameField = $('#'+id+'>td>input[name=name]');
	var phoneField = $('#'+id+'>td>input[name=phone]');
	var emailField = $('#'+id+'>td>input[name=email]');
	
	nameField.attr('readonly',false).attr('class','edit');
	phoneField.attr('readonly',false).attr('class','edit');
	emailField.attr('readonly',false).attr('class','edit');
	
	//save out the original values in case cancel
	nameField.attr('data_name',nameField.val());
	phoneField.attr('data_phone',phoneField.val());
	emailField.attr('data_email',emailField.val());
	
	$('#'+id+'>td>[name=changeButton]').css('visibility','visible');
	$('#'+id+'>td>[name=cancelButton]').css('visibility','visible');
}


function cancelChanges(id){

	var nameField = $('#'+id+'>td>input[name=name]');
	var phoneField = $('#'+id+'>td>input[name=phone]');
	var emailField = $('#'+id+'>td>input[name=email]');
	
	nameField.attr('readonly',true).attr('class','');
	phoneField.attr('readonly',true).attr('class','');
	emailField.attr('readonly',true).attr('class','');
	
	var originalName = nameField.attr('data_name');
	var originalPhone = phoneField.attr('data_phone');
	var originalEmail = emailField.attr('data_email');

	//reset to original values
	nameField.val(originalName);
	phoneField.val(originalPhone);
	emailField.val(originalEmail);
	
	$('#'+id+'>td>[name=changeButton]').css('visibility','hidden');
	$('#'+id+'>td>[name=cancelButton]').css('visibility','hidden');
}


function updateContact(id){

	var name = $('#'+id+'>td>input[name=name]').val();
	var phone = $('#'+id+'>td>input[name=phone]').val();
	var email = $('#'+id+'>td>input[name=email]').val();

	$.post('functions.php',
		   {do: 'updateContact',
		    name: name,
		    phone: phone,
		    email: email,
		    id: id},
		    function(data){
		    	if(data == 'fail'){
		    		alert('Something went wrong. Contact not updated');
		    	}
		    	else{
		    	    //it worked, reset the colors
		    	    var nameField = $('#'+id+'>td>input[name=name]');
					var phoneField = $('#'+id+'>td>input[name=phone]');
					var emailField = $('#'+id+'>td>input[name=email]');
		    	
		    		nameField.attr('readonly',true).attr('class','');
					phoneField.attr('readonly',true).attr('class','');
					emailField.attr('readonly',true).attr('class','');
				
		    		$('#'+id+'>td>[name=changeButton]').css('visibility','hidden');
					$('#'+id+'>td>[name=cancelButton]').css('visibility','hidden');
 		    	}
			});  
}

function deleteContact(id){

	$.post('functions.php',
		   {do: 'deleteContact',
		   id: id},
		   function(data){
		   	  if(data == 'success'){
		   	  	$('#'+id).remove();
		   	  }else{
		   	  	alert('There was an error while deleting contact.');
		   	  }	
		   });	   
}


function search(){
	var searchValue = $('#searchInput').val().toLowerCase();
	var name='';
	
	//reset list so get all results
	showAllContacts();

	results = $('tr').filter(function(){
		name = $(this)[0].cells[0].children[0].value.toLowerCase();
		if(!name.includes(searchValue)){
			return $(this).hide();
		}
	});
}

function showAllContacts(){
	$('tr').each(function(){
		$(this).show();
	});
}

</script>


<style>

input,button{
	border-radius:5px;
}


tr input{
	border:none;
}

.delete{
	background-color:#e89180;
}

.edit {
	background-color:#e6d47e;
}

.save{
	background-color:#7ee6a2;
}

.change{
	visibility:hidden;
	background-color:#7ee6a2;
}

.cancel{
	visibility:hidden;
	background-color: grey;
}

div{
	padding-bottom:50px;
}

.search{
	background-color:skyblue;
}

.show{
	background-color:#cfb4ed;
}

</style>


<title>ContactsApp</title>
</head>

<body>

<?php
refreshPage();
?>

</body>

</html>
