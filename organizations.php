<!DOCTYPE html>
<html lang="ru">
  <head>
  <meta charset="UTF-8">
      <title>Организации</title>
    <style>
    .error {
	border: 2px solid red;
	}
  .hidden{
	display:none;
	}
body { margin:0;
	display:flex;
	flex-direction:column;
text-align:center;
font-size: 18px;
}
.error {
	border: 2px solid red;
	}
table{
  position: relative;
    width: auto;
    margin: 10px;
    border-spacing: 10px;
}
header {display:flex;
flex-direction: column;
text-align: center;
}
button{
  padding: 10px 20px;
  
}
.modal {
    position: fixed; 
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: rgba(0,0,0,0.5); 
    z-index: 1050;
    opacity: 0; 
    -webkit-transition: opacity 200ms ease-in; 
    -moz-transition: opacity 200ms ease-in;
    transition: opacity 200ms ease-in; 
    pointer-events: none; 
    margin: 0;
    padding: 0;
}

.modal:target {
    opacity: 1; 
   pointer-events: auto; 
    overflow-y: auto; 
}
.modal-dialog {
    position: relative;
    width: auto;
    margin: 10px;
}
@media (min-width: 576px) {
  .modal-dialog {
      max-width: 500px;
      margin: 100px auto; 
  }
}
.modal-content {
    position: relative;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    background-color: #fff;
    -webkit-background-clip: padding-box;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.2);
    border-radius: .3rem;
    outline: 0;
}
@media (min-width: 768px) {
  .modal-content {
      -webkit-box-shadow: 0 5px 15px rgba(0,0,0,.5);
      box-shadow: 0 5px 15px rgba(0,0,0,.5);
  }
}
.modal-header {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    -webkit-justify-content: space-between;
    -ms-flex-pack: justify;
    justify-content: space-between;
    padding: 15px;
    border-bottom: 1px solid #eceeef;
}
.modal-title {
    margin-top: 0;
    margin-bottom: 0;
    line-height: 1.5;
    font-size: 1.25rem;
    font-weight: 500;
}
.close {
    float: right;
    font-family: sans-serif;
    font-size: 24px;
    font-weight: 700;
    line-height: 1;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    opacity: .5;
    text-decoration: none;
}
.close:focus, .close:hover {
    color: #000;
    text-decoration: none;
    cursor: pointer;
    opacity: .75;
}
.modal-body {
  position: relative;
    -webkit-box-flex: 1;
    -webkit-flex: 1 1 auto;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 15px;
    overflow: auto;
}
.modal-footer{
  position: relative;
    -webkit-box-flex: 1;
    -webkit-flex: 1 1 auto;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 15px;
    overflow: auto;
}
#footer {
    position: fixed; 
    left: 0; bottom: 0; 
    padding: 10px; 
    width: 100%; 
   }
  #addorg{
    margin: auto;
    align-items: center;
    display: block;
    max-width: 200px; 
  }
    </style>
    </head>
    <body>
    <header>
<h1>Организации</h1>
</header>
<button id="addorg"><a href="#openModalADD">Добавить организацию</a></button>
<?php
header('Content-Type: text/html; charset=UTF-8');
$serverName = "HP-ENVY-BUK"; 
$connectionInfo = array( "Database"=>"TaskBase", "UID"=>"sa", "PWD"=>"Vlad2002", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn==false ) {
     echo "Соединение не установлено.<br />";
     die( print_r( sqlsrv_errors(), true));
}
$id=0;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if(empty($_POST['id'])){
    if(!empty($_POST['ancestor_name']))
    {
      $sql = "SELECT id FROM Organization WHERE name=?";
  $stmt = sqlsrv_query( $conn, $sql,array($_POST['ancestor_name']));
  if( $stmt === false ) {
       die( print_r( sqlsrv_errors(), true));
  }
  $id=0;
  while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
  $id=$row['id'];
  }
  $sql = "SELECT name FROM Organization WHERE name=? ";
  $stmt = sqlsrv_query( $conn, $sql, array($_POST['name']));
      if( $stmt === false ) {
        die( print_r( sqlsrv_errors(), true));
    }
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
      if($row['name']!= ""){
        
        $id=-1;}
    }
    sqlsrv_free_stmt( $stmt);
    if($id==-1)
{
  echo '<div class="error">Компания с таким наименованием уже существует</div>';
}
else{
      $sql = "INSERT INTO Organization VALUES(?, ?, ?, ?)";
      $stmt = sqlsrv_query( $conn, $sql, array($_POST['name'], $_POST['adress'], $_POST['phone'], $id));
      if( $stmt === false ) {
        die( print_r( sqlsrv_errors(), true));
      }
      header('Location: organizations.php');
   }
    }
    else{
      $id=0;
      $sql = "SELECT name FROM Organization WHERE name=? ";
  $stmt = sqlsrv_query( $conn, $sql, array($_POST['name']));
      if( $stmt === false ) {
        die( print_r( sqlsrv_errors(), true));
    }
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
      if($row['name']!= ""){
        
        $id=-1;}
    }
    sqlsrv_free_stmt( $stmt);
    if($id==-1)
{
  echo '<div class="error">Компания с таким наименованием уже существует</div>';
}
else{
      $sql = "INSERT INTO Organization VALUES( ?, ?, ?, ?)";
      $stmt = sqlsrv_query( $conn, $sql,array($_POST['name'], $_POST['adress'], $_POST['phone'], NULL));
      if( $stmt === false ) {
        die( print_r( sqlsrv_errors(), true));
   }
   header('Location: organizations.php');}
  }
  // 
}
else
{
  if(empty($_POST['name']))
  {
    $sql = "DELETE FROM Organization WHERE id=?";
      $stmt = sqlsrv_query( $conn, $sql, array($_POST['id']));
      if( $stmt === false ) {
        die( print_r( sqlsrv_errors(), true));
    }
    header('Location: organizations.php');
  }
  else{
  $id=$_POST['ancestor'];
    $sql="WITH RecursiveQuery (id, ancestor, name)
  AS
  (SELECT id, ancestor, name
 FROM Organization dep
 WHERE dep.id = ?
 UNION ALL
 SELECT dep.id, dep.ancestor, dep.name
 FROM Organization dep
 JOIN RecursiveQuery rec ON dep.ancestor = rec.id)
  SELECT id FROM RecursiveQuery";
  $stmt = sqlsrv_query( $conn, $sql,array($_POST['id']));

  while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
  if($id==$row['id']){$id=-1;}
}
sqlsrv_free_stmt( $stmt);
if($id==-1)
{
  echo '<div class="error">Родительская компания данной компании не может быть дочерней для той компании, которая является дочерней для данной или является дочерней для дочерней компании данной компании </div>';
}
else
{
  $sql = "SELECT id FROM Organization WHERE name=? ";
  $stmt = sqlsrv_query( $conn, $sql, array($_POST['name']));
      if( $stmt === false ) {
        die( print_r( sqlsrv_errors(), true));
    }
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
      if($_POST['id']!=$row['id']){
        
        $id=-1;}
    }
    sqlsrv_free_stmt( $stmt);
    if($id==-1)
{
  echo '<div class="error">Компания с таким наименованием уже существует</div>';
}
else{
    $sql = "UPDATE Organization SET name=?, adress=?, phone=?, ancestor=? WHERE id=?";
      $stmt = sqlsrv_query( $conn, $sql, array($_POST['name'], $_POST['adress'],$_POST['phone'],$id, $_POST['id']));
      if( $stmt === false ) {
        die( print_r( sqlsrv_errors(), true));
    }
    sqlsrv_free_stmt( $stmt);
  }
}}}

}?>

<table>
<thead>
    <tr>
      <th>Название организации</th>
      <th>Адрес организации</th>
      <th>Телефон организации</th>
      <th>Родительская организация</th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
<?php
  $sql = "SELECT O.id, O.name, O.adress, O.phone, P.name as ancestor_name FROM Organization O LEFT JOIN Organization P ON O.ancestor=P.id";
  $stmt = sqlsrv_query( $conn, $sql);
  if( $stmt === false ) {
       die( print_r( sqlsrv_errors(), true));
  }
  
  while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
    echo "<tr class=".$row['id'].">";
    echo "<th>".$row['name']."</th><th>".$row['adress']."</th><th>".$row['phone']."</th><th>".$row['ancestor_name']."</th><th><button id=".$row['id']." onClick = update(this)><a href="."#openModalUpdate".">Изменить</a></button> </th><th><button id=".$row['id']." onClick = deleteorg(this)><a href="."#openModalDelete".">Удалить</a></button></th><th><button  id=".$row['id']." onClick = addchild(this)><a href="."#openModal".">Добавить дочернюю организацию</a></button></th>";
    echo "</tr>";
}
sqlsrv_free_stmt( $stmt);
$sql = "SELECT id, name FROM Organization ";
$stmt = sqlsrv_query( $conn, $sql);
  if( $stmt === false ) {
       die( print_r( sqlsrv_errors(), true));
  }
?>
  </tbody>
  </table>
  <div id="openModalDelete" class="modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Удалить Организацию?</h3>
        <a href="#close" title="Close" class="close">×</a>
      </div>
      <div class="modal-body">    
        Вы уверены в удалении данной организации из списка?
      </div>
      <div class="modal-footer"> 
      <form id="deleteform" action="" method="POST">
      <input id='deleteid' type='text' class='hidden' name='id'/>
      <input type="submit" name="submit" id="submit" value="Да" />
      </form>
      </div>
    </div>
  </div>
</div>
  <div id="openModalUpdate" class="modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Изменить данные организации</h3>
        <a href="#close" title="Close" class="close">×</a>
      </div>
      <div class="modal-body">    
      <form id="updateform" action="" method="POST">
      <input id='updateid' type='text' class='hidden' name='id'/>
      <label> Наименование: </label>
      <input id='updatename' type="text" name="name" placeholder="Наименование"/><br><br>
      <label> Адрес: </label>
      <input id='updateadress' type="text" name="adress" placeholder="Адрес"/><br><br>
      <label> Телефон: </label>
      <input id='updatephone' type="text" name="phone" placeholder="Телефон"/><br><br>
      <label> Родительская организация: </label>
      <select id="updateancestor" name="ancestor">
      <option></option>
      <?php
      while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
        echo "<option value=".$row['id'].">".$row['name']."</option>";
      }
      sqlsrv_free_stmt( $stmt);
      ?>
      </select>
      <br><br><input type="submit" name="submit" id="submit" value="Изменить" />
      </form>
      </div>
    </div>
  </div>
</div>
  <div id="openModal" class="modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Добавить дочернюю организацию</h3>
        <a href="#close" title="Close" class="close">×</a>
      </div>
      <div class="modal-body">    
      <form id="addchildform" action="" method="POST">
      <label> Наименование: </label>
      <input id='childname' type="text"  name="name" placeholder="Наименование"/><br><br>
      <label> Адрес: </label>
      <input id='childadress' type="text" readonly name="adress" placeholder="Адрес"/><br><br>
      <label> Телефон: </label>
      <input id='childphone' type="text" readonly name="phone" placeholder="Телефон"/><br><br>
      <label> Родительская организация: </label>
      <input id='childancestor' type="text" readonly name="ancestor_name" placeholder="Родительская организация"/><br><br>
      <input type="submit" name="submit" id="submit" value="Добавить" />
      </form>
      </div>
    </div>
  </div>
</div>
<div id="openModalADD" class="modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Добавление новой организации</h3>
        <a href="#close" title="Close" class="close">×</a>
      </div>
      <div class="modal-body">    
      <form id="addform" action="" method="POST">
      <label> Наименование: </label>
      <input id='name' type="text" name="name" placeholder="Наименование"/><br><br>
      <label> Адрес: </label>
      <input id='adress' type="text" name="adress" placeholder="Адрес"/><br><br>
      <label> Телефон: </label>
      <input id='phone' type="text" name="phone" placeholder="Телефон"/><br><br>
      <input type="submit" name="submit" id="submit" value="Добавить" />
      </form>
      </div>
    </div>
  </div>
</div>
<script>
  function addchild(obj){
    
    const adress=document.getElementById("childadress");
    const phone=document.getElementById("childphone");
    const ancestor=document.getElementById("childancestor");
    const row=document.getElementsByClassName(obj.id);
    adress.value=row[0].cells[1].textContent;
    phone.value=row[0].cells[2].textContent;
    ancestor.value=row[0].cells[0].textContent;
  }
  function deleteorg(obj){
    const delid=document.getElementById("deleteid");
    delid.value=obj.id;
  }
  function update(obj){
    const id=document.getElementById("updateid");
    const name=document.getElementById("updatename");
    const adress=document.getElementById("updateadress");
    const phone=document.getElementById("updatephone");
    const row=document.getElementsByClassName(obj.id);
    const ancestor=document.getElementById('updateancestor');
    var index;
for (index = 0; index < ancestor.options.length; ++index) {
if(ancestor.options[index].text==row[0].cells[3].textContent)
ancestor.value=ancestor.options[index].value;
}
    id.value=obj.id;
    name.value=row[0].cells[0].textContent;
    adress.value=row[0].cells[1].textContent;
    phone.value=row[0].cells[2].textContent;
  }

</script>
<div id="footer">
   Выполнил Дудкин В. А.
  </div>
</body>
</html>