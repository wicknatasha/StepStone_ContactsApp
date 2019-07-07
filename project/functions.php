<?php

$db = new SQLite3('contactsApp_nw19.db');

if($db){
	$db->exec('CREATE TABLE IF NOT EXISTS contacts (name varchar(50), phone varchar(20), email varchar(50))'); 
	//permissions so db can write to the file
	chmod('contactsApp_nw19.db', 0755);
}
else{
	echo 'ERROR creating database for Contacts App. Check permissions on the project folder.';
}
	
	
if(isset($_POST['do'])){
	switch($_POST['do']){
		case 'addContact':
			addContact($_POST['name'],$_POST['phone'],$_POST['email']);
			break;
		case 'updateContact':
			updateContact($_POST['name'],$_POST['phone'],$_POST['email'], $_POST['id']);
			break;
		case 'deleteContact':
			deleteContact($_POST['id']);
			break;
	}
}


#Add a new contact
function addContact($name, $phone, $email){
	global $db;
echo 'what is happening';	
	$sql = $db->prepare("INSERT INTO contacts VALUES (:name, :phone, :email)");
	$sql->bindValue(':name',$name);
	$sql->bindValue(':phone',$phone);
	$sql->bindValue(':email',$email);
	
	$result = $sql->execute();
	$noError = $db->lastErrorMsg() == 'not an error'? true : false;
	
	if($noError){
		echo 'success';
		refreshPage();
		
	}else{
		echo 'fail';
	}
}

#Update a contact
function updateContact($name, $phone, $email, $id){
	global $db;
	$sql = $db->prepare("UPDATE contacts SET name = :name, phone = :phone, email = :email WHERE rowid = :id");

	$sql->bindValue(':name',$name);
	$sql->bindValue(':phone',$phone);
	$sql->bindValue(':email',$email);
	$sql->bindValue(':id',$id);
	
	$result = $sql->execute();
	$noError = $db->lastErrorMsg() == 'not an error'? true : false;
	
	if($noError){
		echo 'success';
	}else{
		echo 'fail';
	}
}


#Delete a contact
function deleteContact($id){
	global $db;
	$sql = $db->prepare("DELETE FROM contacts WHERE rowid = :id");
	$sql->bindValue(':id',$id);
	
	$result = $sql->execute();
	$noError = $db->lastErrorMsg() == 'not an error'? true : false;
	
	if($noError){
		echo 'success';
	}else{
		echo 'fail';
	}
}

#Retrieve entire list of contacts
function getAllContacts(){
	global $db;
	$contacts = $db->query("SELECT rowid, * FROM contacts ORDER BY name COLLATE NOCASE ASC");
	
	echo "<table>";
	while($contact = $contacts->fetchArray()){
 		echo "
 		<tr id={$contact['rowid']}>
			<td><input type=text name='name' readonly='true' value='{$contact['name']}' ></input></td>
			<td><input type=text name='phone' format='{ readonly='true' value='{$contact['phone']}'></input></td>
			<td><input type=text name='email' readonly='true' value='{$contact['email']}'></input></td>
			<td><button onClick='deleteContact({$contact['rowid']});' class='delete' >Delete</button></td>
			<td><button onClick='editContact({$contact['rowid']});' class='edit' name='editButton' >Edit</button></td>
			<td><button onClick='updateContact({$contact['rowid']});' class='change' name='changeButton' >Save</button></td>
			<td><button onClick='cancelChanges({$contact['rowid']});' class='cancel' name='cancelButton'>Cancel</button></td>
 		</tr>";
	}
 	echo "</table>";
}

function refreshPage(){

	//not putting any validation on new contacts, letting user type whatever they need
	echo "
	<div>
	<h4>Add Contact</h4>
		<input type=text placeholder='Name' name='name' id='name' maxlength=50 />
		<input type=text placeholder='Phone' name='phone' id='phone' maxlength=20 />
		<input type=text placeholder='Email' name='email' id='email' maxlengt=50 />
		<button onClick='addContact();' class='save' >Save New Contact</button>
	</div>
	<h4>Search Contacts By Name</h4>
		<input type=text id='searchInput' maxlength=50 />
		<button onClick='search();' class='search' >Search</button>
		<button onClick='showAllContacts()' class='show' >Show All Contacts</button>
	<div>
		
	</div>
	";

	echo "<h4>Contacts:\n</h4>";
	getAllContacts();
}

?>
