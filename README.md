Recipe Finder - PHP Coding Test
Given a list of items in the fridge (presented as a csv list), and a
collection of recipes (a collection of JSON formatted recipes),
produce a recommendation for what to cook tonight.
Program should be written to take two inputs; fridge csv list, and
the json recipe data. How you choose to implement this is up to you;
you can write a console application which takes input file names as
command line args, or as a web page which takes input through a form.
The only rule is that it must run and return a valid result using the
provided input data.
Input:
fridge.csv
Format: item, amount, unit, useby
Where;
● Item (string) = the name of the ingredient – e.g. egg)
● Amount (int) = the amount
● Unit (enum) = the unit of measure, values;
● of (for individual items; eggs, bananas etc)
● grams
● ml (milliliters)
● slices
● UseBy (date) = the use by date of the ingredient
(dd/mm/yy)
e.g.
bread,10,slices,25/12/2017
cheese,10,slices,25/12/2017
butter,250,grams,25/12/2017
peanut butter,250,grams,2/12/2017
mixed salad,500,grams,26/12/2016
recipes.json
Array of recipes with format specified as below

● name : String
● ingredients[]
● item : String
● amount : int
● unit : enum
e.g.

[
{
"name":"grilledcheeseontoast",
"ingredients":[
{
"item":"bread",
"amount":"2",
"unit":"slices"
},
{
"item":"cheese",
"amount":"2",
"unit":"slices"
}
]
},
{
"name":"saladsandwich",
"ingredients":[
{
"item":"bread",
"amount":"2",
"unit":"slices"
},
{
"item":"mixedsalad",
"amount":"200",
"unit":"grams"
}
]

}
]
Notes:
● An ingredient that is past its useby date cannot be used for
cooking.
● If more than one recipe is found, then preference should be given
to the recipe with the closest useby item
● If no recipe is found, the program should return “Order Takeout”
● Program should be all-inclusive and a run script included
● Please provide a complete copy of a git repository, or a link to a
github/public repo
○ We want to be able to see your commit history
● Include unit tests
● All code to be completed using php.
Using the sample input above, the program should return "salad sandwich".
