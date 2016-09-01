<?php

class Member
{
	public $data;
	public $name;
	public $age;
	public $email;
	public $created_at; 

	const DSN = 'mysql:host=localhost;dbname=homework7_class;charset=utf8;';
	const USER = 'root';
	const PASSWORD = 'root';
	
	public function set($member)
	{
		$this->data = $member;
		$this->name = $member['name'];
		$this->age = $member['age'];
		$this->email = $member['email'];
		$this->created_at = date("Y-m-d H:i:s"); 
	}

	public function insert()
	{
		try {
			$dbh = new PDO(self::DSN, self::USER, self::PASSWORD);
				// echo '成功しました！';
		} 
		catch (PDOException $e) {
			echo $e->getMessage();
			exit;
		}

		$sql = "insert into members (name, age, email, created_at) values (:name, :age, :email, :created_at)";
		$stmt = $dbh->prepare($sql);
		
		$name = $this->name;
		$age = $this->age; 
		$email = $this->email;
		$created_at = $this->created_at; 

		$stmt->bindParam(":name", $name);
		$stmt->bindParam(":age", $age);
		$stmt->bindParam(":email", $email);
		$stmt->bindParam(":created_at", $created_at);

		$result = $stmt->execute();
		// print_r($stmt->errorInfo());
		
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	public function findByEmail($email)
	{
		try {
			$dbh = new PDO(self::DSN, self::USER, self::PASSWORD);
				// echo '成功しました！';
		} 
		catch (PDOException $e) {
			echo $e->getMessage();
			exit;
		}

		$sql = "select * from members where email = :email";

		$stmt = $dbh->prepare($sql);

		$stmt->bindParam(":email", $email);

		$result = $stmt->execute();
		
	        $members = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($members) {
			return array( 
			'id' => $members['id'], 
			'name' => $members['name'], 
			'age' => $members['age'], 
			'email' => $members['email'], 
			'created_at' => $members['created_at'], 
			); 
		} else {
			return false;
		}
	}

	public function delete($id)
	{
		try {
			$dbh = new PDO(self::DSN, self::USER, self::PASSWORD);
				// echo '成功しました！';
		} 
		catch (PDOException $e) {
			echo $e->getMessage();
			exit;
		}

		$sql = "delete from members where id = :id";

		$stmt = $dbh->prepare($sql);

		$stmt->bindParam(":id", $id);

		$result = $stmt->execute();

		if ($result) {
			return true;
		} else {
			return false;
		}
	}
}

// members テーブルのデータを表します。 
$member = new Member();

// メンバーのデータをセットします。 
$member->set(array( 
'name' => 'テスト名', 
'age' => 30, 
'email' => 'test@example.com', 
));

// $member->set() でセットしたデータを members テーブルに追加登録します。 
// この時 created_at カラムに現在日時を自動的にセットするようにしてください。 
// 登録が成功した場合は true 、失敗した場合は false を返します。 
$result = $member->insert();
var_dump($result);
echo "<br>";

// 引数で指定されたメールアドレスのユーザーを members テーブルから探し、 
// もし見つかった場合、そのデータを以下の形式で返します。 
// array( 
// 'id' => 'members テーブル の id カラムの値', 
// 'name' => 'members テーブル の name カラムの値', 
// 'age' => 'members テーブル の age カラムの値', 
// 'email' => 'members テーブル の email カラムの値', 
// 'created_at' => 'members テーブル の created_at カラムの値', 
// ); 
// // ユーザーが見つからなかった場合、false を返します。 
$data = $member->findByEmail('test@example.com');
var_dump($data);
echo "<br>";

// // 引数で指定された id を持つ members テーブルのレコードを削除します。 
// // 削除が成功した場合は true 、失敗した場合は false を返します。 
$result = $member->delete($data['id']);
var_dump($result);
echo "<br>";
//
// // ここでは false が返ってくるはずです。 
$data = $member->findByEmail('test@example.com'); 
var_dump($data);
echo "<br>";

?>
