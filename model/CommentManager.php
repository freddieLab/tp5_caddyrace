<?php 
  
namespace FredLab\tp5_caddy_race\Model;

require_once("model/Manager.php");

class CommentManager extends Manager { // se situe dans le namespace

//**************************************************************************************
//                                Model CommentManager           
//**************************************************************************************

    public function getItemsGene($aisleGeneId) {
        $db = $this->dbConnect();
        $itemsGene = $db->prepare('SELECT item_gene_name FROM items WHERE aisle_gene_id = ? ORDER BY item_gene_name');
        $itemsGene->execute(array($aisleGeneId));    
        return $itemsGene;
    }
    
    public function getCommentsCount($postId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT COUNT(post_id) AS nbre_comment FROM comments WHERE post_id = ?');
        $req->execute(array($postId));
        $commentsCount = $req->fetch();
        $req->closeCursor();
        return $commentsCount;
    }

    public function getMemberNoComment($member) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT block_comment FROM members WHERE pseudo = ?');
        $req->execute(array($member));
        $addCommentRight = $req->fetch();
        $req->closeCursor();
        return $addCommentRight;
    }
    
   public function pushItemGene($aisleGeneId, $itemGeneName) {            
        $db = $this->dbConnect();
        $req = $db->prepare('INSERT INTO items(aisle_gene_id, item_gene_name) VALUES(:aisle_gene_id, :item_gene_name)');
        $req->execute(array(
            'aisle_gene_id' => $aisleGeneId,
            'item_gene_name' => $itemGeneName
        ));
        $req->closeCursor();
    }
    
   public function replaceComment($commentId, $newComment) {            
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE comments SET comment = :comment, comment_date = NOW() WHERE id = :commentId');
        $req->execute(array(
            'comment' => $newComment,
            'commentId' => $commentId
        ));
        $req->closeCursor();
    }
    
    public function signalComment($commentId, $signalId, $member) {  
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE comments SET comment_signal = :comment_signal, signal_author = :member, signal_date = NOW() WHERE id = :commentId');
        $req->execute(array(
            'comment_signal' => $signalId,
            'member' => $member,
            'commentId' => $commentId
        ));  
        $req->closeCursor();
    }
    
    public function getSignalComments() {
        $db = $this->dbConnect();
        $getSignalComments = $db->query('SELECT id, post_id, author, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin\')comment_date_fr, signal_author, DATE_FORMAT(signal_date, \'%d/%m/%Y à %Hh%imin\')signal_date_fr FROM comments WHERE comment_signal = 1 ORDER BY comment_date');
        $signalComments = array(); 
        while ($signalComment = $getSignalComments->fetch()) {
            $signalComments[] = $signalComment; // on créer un tableau regroupant les members
        }
        return $signalComments;
    }
    
    public function deleteComment($commentId) {  
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM comments WHERE id = :idnum');
        $req->execute(array(
            'idnum' => $commentId
        ));  
        $req->closeCursor();
    }
    
    public function deleteComments($postId) {  
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM comments WHERE post_id = :postidnum');
        $req->execute(array(
            'postidnum' => $postId
        ));  
        $req->closeCursor();
    }
    
}
