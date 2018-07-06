				---------------Franc Rusha Web Programming Project---------------
                                                       RSS Feed Aggregator

---Database Tables---

1.users: holds registered user data.
2.rssfeeds: holds each feed used to retrieve articles, entered manually.
3.feed_items: holds every news article as a record, inserted by the 'getFeedItems.php' script, retrieved when webpage is loaded.
4.user_feed: holds a composite key of user_id and feed_id, indicating a user's preference for a certain feed; inserted by 'populateInterests.php' script.

---PHP Files---

1. dbconn.php : creates and returns database connection.

2. getFeedItems.php : populates feed_items table with news articles from each feed in rssfeeds table. Ideally this would be executed periodically as a cron job, but I couldn't find an effective way to do it on Windows, so I executed it manually.

3. userHandler.php : contains manageUser class with functions to manage user login and registration; included in 'mainPage.php' and 'userPage.php'.

4. mainPage.php : The homepage of the website. Contains news articles divided by category into tabs, login and signup buttons.

5. userPage.php : The homepage for a logged user to the site. Contains an extra tab to show articles that interest the user. The user can set their interests by clicking on "Choose your interests" button, checking/unchecking the relevant checkboxes and clicking "Save".

6. populateInterests.php : called when the user clicks "Save" on the ChooseYourInterests modal form; populates the user_feed table with user_id/feed_id pairs.

7. logout.php : called when user clicks on "Logout" link; logs out the user.

8. getNewsContent.php, getBusinessContent.php, getTechContent.php, getSportsContent.php, getEntertainmentContent.php : retrieve news articles from feed_items table for each category.

9. getCustomizedContent.php : retrieves news articles based on user interests to display them in the "My interests" tab.