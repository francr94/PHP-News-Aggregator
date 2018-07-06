<?php

	include_once('dbconn.php');
	$conn = getDB(); //get database connection
	
	try {
		$conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//get all rss feeds
		$select_query = "SELECT * FROM rssfeeds";
		$result = $conn -> query($select_query);
		
		if ($result -> rowCount() > 0) {
		
			while ($row = $result -> fetch()) {
				
				//for each rss feed, pass its url to simplexml_load_file to parse the feed items
				$feedurl = $row['url'];
				$xml = simplexml_load_file($feedurl);
				
				//parse and insert 20 feed items into database
				for ($i = 0; $i < 20; $i++) {
					
					$title = $xml -> channel -> item[$i] -> title;
					$link = $xml -> channel -> item[$i] -> link;
					$pubDate = $xml -> channel -> item[$i] -> pubDate;
					$namespaces = $xml -> getNamespaces("true");
					$image = "";
					if (strpos($row['title'],'BBC') !== false)
						$image = $xml -> channel ->item[$i] -> children($namespaces["media"]) -> thumbnail -> attributes() -> url;
					$description = $xml -> channel -> item[$i] -> description;
					$category = $row['category'];
					$feed_id = $row['id'];
					
					//insert feed item into feed_items table
					$insert_query = "INSERT IGNORE INTO feed_items (feed_id, title, pub_date, description, url, image_url, category, log_time) VALUES "
					                  ."(:feed_id, :title, :pub_date, :description, :url, :image_url, :category, NOW())";
									  
					$stmt = $conn -> prepare($insert_query);
					
					$stmt -> bindParam(':feed_id',$feed_id);
					$stmt -> bindParam(':title',$title);
					$stmt -> bindParam(':pub_date',$pubDate);
					$stmt -> bindParam(':description',$description);
					$stmt -> bindParam(':url',$link);
					$stmt -> bindParam(':image_url',$image);
					$stmt -> bindParam(':category',$category);
					
					$stmt -> execute();
				}
			}
			echo "Records inserted successfully";
			unset($result);
		}
	}
	catch (PDOException $e){
		die("ERROR".$e -> getMessage());
	}
	
	//close connection
	unset($conn);
?>