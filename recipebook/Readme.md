# Recipebook

Recipebook is an application that allows users to share their personal recipes as well as explore other user's recipes.  The application allows a following system so users can view updates on new content and when other users follow them.  Users can add new recipes, share recipes with social sites like Facebook, as well as favorite other user’s recipes.

## Live Site

Navigate to "http://www.bradcerny.com".


>#### User 1: test
>Email: 'user@email.com'
>Password: '12345'

## Installing

1. 	Create a database name 'rb_db'.

2.	Import 'recipebook_db.sql'.

3.	Move 'rb_application' folder to your 'htdocs' folder.

4.	Start MAMP.

5.	Navigate to 'localhost:XXXX/rb_application/' in your browser.

6. JS file (main.js) is setup to use 'localhost:8888', change URL variables if needed.

## Testing

Navigating to 'localhost:XXXX/rb_application/' will present you with the home screen.


>#### User 1: test
>Email: 'user@email.com'
>Password: '12345'

After logging in, the user can search for a recipe by ingredient or recipe name from the home page or using the search in the header on sub pages.  When a recipe tile is clicked, the user will be directed to a recipe details page.  This page shows detailed recipe information which includes ingredients and directions, as well as the user who posted the recipe.  Also, on the details page, a user can comment on the recipe using the Disqus comment system.  A user can view another user’s profile page directly from the recipe details page by clicking on the username in the “Recipe Added By” section.  The user profile page allows the logged in user to follow the user being viewed, as well as displays information about the user including followers, recipes added, and recipes favorited.  The logged in user can access the dashboard by clicking on their username on the right side of the header.  The dashboard shows updates on new followers and new recipes.  A user can also access their recipes from the dashboard by clicking on “Your Recipes”.  Users can add a recipe by selecting the “Add Recipe” button in the header.  This will guide them through a step by step process of adding a new recipe.
