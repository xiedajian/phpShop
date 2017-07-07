<?php 

/**
* 
*/
class articleControl
{
	public function getarticleOp() {
        $article_id = intval($_GET ['article_id']);

        $model_article = Model('article');
        $artical = $model_article -> getOneArticle($article_id);

        output_data($artical);
    }
}


