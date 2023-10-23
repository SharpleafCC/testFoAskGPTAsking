<?php
if ( !class_exists('DataLayer') ) {
    class DataLayer {
        /**
         * Finds all spcific terms of a post and returns a string that contains the slug of each
         * term separated by a comma
         * 
         * @param int $postId
         * @param string $termName - the term name
         * 
         * @return string the single string
         */
        public function getTermSlugsSeparatedByComma( $postId, $termName ) {
            $termsString = '';

            $terms = get_the_terms($postId, $termName);

            if ( !empty($terms) ) {
                $termsSlugArray = array_column($terms, 'slug');
                $termsString = implode(',', $termsSlugArray);
            }

            return $termsString;
        }
    }
}
?>