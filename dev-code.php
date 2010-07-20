<?php
    if (!empty($atts['category']))
        {
            // Convert to array.
            $categoryIDs = explode(',', $atts['category']);

            // Add any children categories that are present
            $size = count($categoryIDs);
            
            //Logical OR all children categories with their parents then Logical and the results
            // Each category creates a new line for that id in the database so have to quary each category seperatly
            $i = 0;
            $entryIDs = $wpdb->get_col("SELECT DISTINCT entry_id FROM " . CN_TERM_RELATIONSHIP_TABLE );
            $catString = implode("','", $entryIDs);
            //print( "catString=$catString\nTotal entries=" . count($entryIDs) . "\n"); //Debug print
            do
            {
                //Set up for child categories loop
                $childCategoryIDs = NULL;
                $childCategoryIDs = $this->categoryChildrenIDs( $categoryIDs[$i] ); //Recursive function finds children through all levels
                $size2 = count($childCategoryIDs);
                //print( "Category $categoryIDs[$i] childCount=$size2\n" ); //Debug print
                
                //Get the ids matching the parent category
                $childIDs = $wpdb->get_col( "SELECT DISTINCT entry_id FROM " . CN_TERM_RELATIONSHIP_TABLE . " WHERE term_taxonomy_id='" .  $categoryIDs[$i] . "'" );
                //print( "ids matching parrent category=" . count($childIDs) . "\n" ); //Debug print
                
                //OR all children into the goup (Put all children entries to this parrent in the id list)
                for ( $j = 0; $j < $size2; $j++ )
                {
                    $thisChild = $wpdb->get_col( "SELECT DISTINCT entry_id FROM " . CN_TERM_RELATIONSHIP_TABLE . " WHERE term_taxonomy_id='" .  $childCategoryIDs[$j] . "'" );
                    //print ("id's matching category $childCategoryIDs[$j]=" . count($thisChild) . "\n"); //Debug Print
                    
                    //It is possible for one or both arrays to be empty. In which case the array merge will fail. so check
                    //If neither is empty merge the 2 and continue
                    if ( $childIDs != NULL && $thisChild != NULL )
                    {
                        $childIDs = array_merge( $childIDs,  $thisChild );
                        //print ("Post merge childIDs count=" . count($childIDs) . "\n");
                        
                    }
                    //One of the two must be null if the this child id is not null the child
                    // id must be null. So just move this child id to child id
                    else if ( $thisChildIDs != NULL )
                    {
                        $childIDs = $thisChildIDs;
                    }
                    //If this child id is null there is nothing to add to the already compiled list
                    // so no action is neccesary
                    //If both are null nothing can be done so again take no action.
                }

                //print( "Parent and all children=" . count($childIDs) . "\n" ); //Debug Print
                
                //Do a diff of current id list and child id list. On keep the id that are in both lists.
                //  could write a for loop to do this but having sql to do it for me is so much cooler.
                //$entryIDs = $wpdb->get_col( "SELECT DISTINCT entry_id FROM " . CN_TERM_RELATIONSHIP_TABLE . " WHERE entry_id IN ('" . $catString . "') AND entry_id IN ('" . $childCatString . "')" );
                //On second thought have php do it. Now I have to check for null though
                if ( $entryIDs != NULL && $childIDs != NULL )
                {
                    $entryIDs = array_intersect( $entryIDs, $childIDs );
                }
                else
                {
                    //If either array is null no category matches the desired context.
                    $entryIDs = NULL;
                }

                //print( "Total found=" . count($entryIDs) . "\n"); //Debug Print
                
                if ( $entryIDs != NULL )
                {
                                $catString = implode("','", $entryIDs);
                    //print( "i=$i catString=$catString\n" ); //Debug Print
                            }
                $i++;
                        } while ( $i < $size );

            //Test code so I can test my sql commands and see if they work the way I expect them to
                //$entryIDs = $wpdb->get_col( "SELECT DISTINCT entry_id FROM " . CN_TERM_RELATIONSHIP_TABLE . " WHERE entry_id IN ('2','5') AND entry_id IN ('2','3')" );
                        //$catString = implode("','", $entryIDs);
            //print( "i=$i catString=$catString\n" );
            
            if (!empty($entryIDs))
            {
                $entryIDs = implode("', '", $entryIDs);
            }
            else
            {
                $entryIDs = "'NONE'";
            }
        }
?>