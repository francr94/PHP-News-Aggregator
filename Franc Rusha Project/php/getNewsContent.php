<?php
	include_once('dbconn.php');
	
	$conn = getDB(); //get db connection
	
	//query to get db items
	$query = "SELECT * from feed_items WHERE category = 'news' ORDER BY pub_date ASC";
	
	
	$page_content = '';// holds the page number links
	$current_page = 1;// holds current page number
	$start = 0; //holds number of first item on page
	
	//if user has clicked on a page sets page number and number of first item
	if (!empty($_POST["page"])) {
		$current_page = $_POST["page"];
		$start=($current_page-1) * 10;
	}
	//sql query portion that limits the number of selected items to 10
	$row_limit=" LIMIT " . $start . "," . "10";
	
	//retrieves all items
	$pagination_query = $conn->prepare($query);
	$pagination_query->execute();

	$row_count = $pagination_query->rowCount();
	if (!empty($row_count)) {
		$page_content .= "<div style='text-align:center;margin:20px 0px;'><form method='post'>";
		$page_count=ceil($row_count/10); //holds number of pages needed to display content
		//if there's more than one page add all page links to $page_content
		if ($page_count>1) {
			for ($i=1;$i<=$page_count;$i++) {
				if ($i==$current_page) {
					$page_content .= '<input type="submit" name="page" value="' . $i . '" class="btn-page current" />';
				} else {
					$page_content .= '<input type="submit" formaction="mainPage.php" name="page" value="' . $i . '" class="btn-page" />';
				}
			}
		}
		$page_content .= "</form></div>";
	}

	//sql query to get feed items 10 at a time
	$content_query = $query.$row_limit;
	$result = $conn -> query($content_query);
	
	if ($result -> rowCount() > 0)
		//display each news item in a container
		while ($row = $result -> fetch())
		{ ?>
					<div class="container">
					  <div class="card card-body bg-light">
						  <div class="media">
							<?php if ($row['feed_id']== 2 || $row['feed_id']== 3 || $row['feed_id']== 4 || $row['feed_id']== 6)
							{ ?>
							<a class="pull-left" href="#" >
								<img class="media-object" src="<?php echo $row['image_url'] ?>" width ="200px" height="140px">
							</a>
							<?php } ?>
							<div class="media-body">
								<h4 class="media-heading"><?php echo $row['title'] ?></h4>
							  <p class="text-right"><?php echo $row['pub_date']?></p>
							  <p><?php echo $row['description']?></p>
							  <a href = "<?php echo $row['url']?>" >Go to source</a>
							</div>
						</div>
					  </div>
					</div>
					<br/>
<?php
		}
	echo $page_content; //display page links at the bottom of the page
?>