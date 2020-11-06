<?php 
include('class-files/Ingredient.php');
include('class-files/Recipe.php');

/**
 * RecipeFinder 
 * 
 */
class RecipeFinder {
    /**
     * Response when nothing is found
     */
    public $msg_webpage = "";

    /**
     * fridgeIngredients 
     *
     */
    private $fridgeIngredients = array();

    /**
     * recipes 
     * 
     */
    private $recipes = array();

    /**
     * Finds recipes from the fridge
     * 
     */
    public function find() {
        $recipeMatches = array();
        
        foreach ($this->recipes as $recipe) {

            $matchedIngredient = $recipe->checkIngredients($this->fridgeIngredients);
            
            if ($matchedIngredient !== false) { 
                $recipeName = $recipe->getName();
                $recipeMatches[$recipeName] = $matchedIngredient;
            }
            
        }

        // no matches?
        if (count($recipeMatches) == 0)
            return $msg_webpage = "Order Takeout";

        // sort by timestamp increasing and return first result
        asort($recipeMatches);

        return key($recipeMatches);
    }

    /**
     * Inputs a list of items in a fridge in a csv format 
     * 
     * @param string $file 
     * @access public
     * @return void
     */
    public function forFridge($file = 'fridge.csv') {
        if (!is_readable($file))
            echo "Unreadable fridge csv file";

        $fp = file($file);

        /* open and read through lines */
        foreach ($fp as $lineNumber => $line) {

            $fields = str_getcsv($line);

            /* catch any line errors so the script can continue */
            try {

                /* add to fridge ingredients */
                $ingredient = new Ingredient($fields[0],
                   $fields[1],
                   $fields[2],
                   $fields[3]);
                
                $this->fridgeIngredients[] = $ingredient;

            } catch (Exception $e) {
                echo "Error! csv line number " . ++$lineNumber . "\n";
            }

            unset($fields, $ingredient);
        }

        return $this;
    }

    public function forRecipes($file = 'recipes.json') {
        if (!is_readable($file)) 
            echo "Unreadable recipes json file";

        $fp   = file_get_contents($file);
        $data = json_decode($fp, true);

        if ($data === null) 
            echo "Could not parse recipes json file";

        foreach ($data as $dataRecipe) {
            try {
                $recipe = new Recipe($dataRecipe['name']);

                foreach ($dataRecipe['ingredients'] as $dataIngredient) {

                    try {
                        $ingredient = new Ingredient($dataIngredient['item'],
                           $dataIngredient['amount'],
                           $dataIngredient['unit']);
                        
                        $recipe->addIngredient($ingredient);
                    } catch (Exception $e) {
                        echo "Error! Invalid ingredient on receipe\n";
                    }
                }

                $this->recipes[] = $recipe;
            } catch (Exception $e) {
                echo "Error! Invalid recipe name\n";
            }
            unset($recipe, $dataIngredient, $ingredient);
        }

        return $this;
    }
}

/* main */
try {

    $target_dir = "data-files/";
    if($_FILES["csvFileToUpload"]["name"] != "" && $_FILES["jsonFileToUpload"]["name"] != ""){
        $arrCSVFileExtention = pathinfo($_FILES["csvFileToUpload"]["name"]);
        $strCSVFileExtention = $arrCSVFileExtention["extension"];
        $arrJSONFileExtention = pathinfo($_FILES["jsonFileToUpload"]["name"]);
        $strJSONFileExtention = $arrJSONFileExtention["extension"];

        if($strCSVFileExtention != "csv"){
            echo $msg_webpage = "Result : Please upload csv file \n";
            return;
        }   

        if($strJSONFileExtention != "json"){
            echo $msg_webpage = "Result : Please upload json file \n";
            return;
        }    
        $target_csv_file = $target_dir . basename($_FILES["csvFileToUpload"]["name"]);
        $target_json_file = $target_dir . basename($_FILES["jsonFileToUpload"]["name"]);

        $recipeFinder = new RecipeFinder();
        echo $msg_webpage = "Result : ".$recipeFinder->forFridge($target_csv_file)
        ->forRecipes($target_json_file)
        ->find();
        echo "\n\n";
    }else{
        echo $msg_webpage = "Result : Files not uploaded \n";
    }
    

} catch (Exception $e) {

    echo $e->getMessage() . "\n";

}

?>
