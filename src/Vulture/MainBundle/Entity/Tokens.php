<?php

/**
 * Tokens: A class that manages the source code converted to tokens.
 *
 * @author Dario Ghilardi
 */

namespace Vulture\MainBundle\Entity;

use Vulture\MainBundle\SecurityLibs\HttpParameterPollution;

class Tokens {
    
    public $filename;    
    public $source;
    public $tokens;
    public $conf;
    
    /**
     * Contructor
     */
    public function __construct($file) {
        $this->filename = $file;
    }
    
    /**
     * Build the full tokens representation of the code.
     *
     * @param type $source
     */
    public function build() {
        
        $this->conf = HttpParameterPollution::getInstance();
        
        $this->source = file_get_contents($this->filename);
        
        $this->tokens = token_get_all($this->source);
        
        // For the strange token_get_all behaviour that leave empty array 
        // elements i need to use array_values to reorder indexes
        $this->tokens = array_values($this->tokens);
                
        // Clean tokens
        $this->clean();
        
        $this->ptReadable();
        
        // Reconstruct arrays into one single token
        $this->manageArrays();
        
        $this->pt();
        $this->ptReadable();
        
        //$this->pat();
    }
    
    /**
     * Delete all tokens to ignore while scanning
     * 
     * Into the tokens array it's unuseful to keep the html code, the array 
     * needs to be light. This function run into his own loop to clean whitespaces
     * as they can be tricky to be removed later.
     *
     * Every token is an array following the convention:
     * [0] => Token numeric index
     * [1] => String content
     * [2] => Line number
     * [3] => Multidimensional array indexes, not involved into this function
     */
	public function clean()
	{
        // Loop through the tokens array
        $ntokens = count($this->tokens);
        
        for ($i = 0; $i < $ntokens; $i++) {
            if( is_array($this->tokens[$i]) ) {
                
                // Mark for removal not needed token types received from the configuration class
                if ( in_array($this->tokens[$i][0], $this->conf->ignore_tokens) ) {
                    unset($this->tokens[$i]);
                }
                
                // Launch additional cleaning from the securitylibs classes
                $this->conf->additionalCleaning();           
                
            } else {        
                
            }
        }
        
        // Rearrange the array as some indexes have been removed
        $this->tokens = array_values($this->tokens);
    }
    
    /**
     * Manage PHP arrays. 
     * 
     * As default arrays are separated into different tokens, because of the brackets. 
     * This method assemble them into one single token with a new array at the index 3,
     * removing brackets:
     * [0] => Token numeric index
     * [1] => String content (ex. $_GET)
     * [2] => Line number
     * [3] => String content of the full array without array name (ex. ['p'])
     * 
     */
    public function manageArrays() {
        
       $ntokens = count($this->tokens);
       
       for ($i = 0; $i < $ntokens; $i++) {
           
            // if i find a variable and the next token is an open bracket
            if (
                (isset($this->tokens[$i][0])) &&
                ($this->tokens[$i][0] == T_VARIABLE) &&
                ($this->tokens[$i+1] == '[')
               ) {
                
                // Setup indexes to cycle through array elements
                $multidimArrayIndex = $i;
                $arrayNavigator = $multidimArrayIndex + 1;
                
                // Setup a counter for brackets
                $brackets = 0;
                
                // Prepare the third element of the array to be filled with a string
                $this->tokens[$multidimArrayIndex][3] = '';

                // Loop through array elements
                while (true) {
                    
                    // Save into the tokens array the string representation of 
                    // the full array
                    if (is_array($this->tokens[$arrayNavigator])) {
                        $this->tokens[$multidimArrayIndex][3] .= $this->tokens[$arrayNavigator][1];
                    } else {
                        $this->tokens[$multidimArrayIndex][3] .= $this->tokens[$arrayNavigator];
                    }
                    
                    // Count brackets, needed to decide when to stop the loop
                    if ($this->tokens[$arrayNavigator] == '[')
                        $brackets++;
                    elseif ($this->tokens[$arrayNavigator] == ']')
                        $brackets--;
                    
                    // remove 
                    unset($this->tokens[$arrayNavigator]);
                    
                    // Next array element
                    $arrayNavigator++; 
                    
                    // Exit from the loop when:
                    // - there're no more tokens into $this->tokens
                    // - it reaches the last ] of the array
                    // The break statement is used instead of complicated 
                    // conditions into the while statement
                    if ( (!isset($this->tokens[$arrayNavigator])) || 
                         ($this->tokens[$arrayNavigator] != '[') && ($brackets == 0))
                        break;
                }
                
                // Remove the last ']' token, leaved in order to end the previous loop
                unset($this->tokens[$arrayNavigator]);
                
                // Put the $i index at the end of the array
                $i = $arrayNavigator;
            }
        }
    }
    
    /**
     * Print tokens into a readable format. Useful to show the tokens but it's
     * important to know that indexes are reformatted during the printing process.
     * 
     * The method print the tokens into a readable format.
     * A foreach needs to be added to print multidimensional arrays on index [3].
     */
    public function ptReadable() {
        $output = array();
        foreach ($this->tokens as $val) {
            if(is_array($val)) {
                $output[] = $this->ft($val);
            } else {
                $output[] = $val;
            }
        }
        return $output;
    }
    
    /**
     * Print tokens as they are.
     * 
     * The method print tokens as they're during the execution. They're not
     * printed into a readable format but it can be useful as ptReadable 
     * reformat indexes too.
     */
    public function pt() {
        echo "<pre>".print_r($this->tokens,1)."</pre>";
    }
    
    
    /**
     * Format tokens.
     * Utility function to be used while printing into a readable format.
     */
    public function ft($array) {
        
        // Convert tokens entities
        if (isset($array[3]))
            $string = htmlentities($array[1].$array[3]) ." - (". $array[0] .") ". token_name($array[0]) ." : $array[2]";
        else
            $string = htmlentities($array[1]) ." - (". $array[0] .") ". token_name($array[0]) ." : $array[2]";
        
        // Remove \n and \r chars
        $string = str_replace("\n", "", $string);
        $string = str_replace("\r", "", $string);
        
        return $string;
    }
    
    /**
     * Print all available tokens and their correspondence.
     */
    public function pat() {
        for($i=258; $i < 376; $i++){
            $res[$i] = token_name($i);
        }
        echo "<style>#tokenCodes td{ white-space:pre; }</style>\n";
        echo "<div id='tokenCodes'><table><tr>\n";
        asort($res);
        echo "<td style='padding-right:1em;'>"; print_r($res); echo "</td>\n";
        ksort($res);
        echo "<td  style='border-left:1px solid black; padding-left:1em;'>"; print_r($res); echo "</td>\n";
        echo "<tr></table></div>\n";
    }
}