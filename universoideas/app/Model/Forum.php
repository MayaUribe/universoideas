<?php
App::uses('AppModel', 'Model');
/**
 * Forum Model
 *
 * @property User $User
 * @property Comment $Comment
 */
class Forum extends AppModel {

    /**
    * Validation rules
    *
    * @var array
    */
    var $virtualFields = array(
        'count' => 'COUNT(Comment.id)',
        'max_comment' => 'MAX(Comment.modified)'
    );
    
    public $validate = array(
        'title' => array(
            'notempty' => array(
                    'rule' => array('notempty'),
                    //'message' => 'Your custom message here',
                    //'allowEmpty' => false,
                    //'required' => false,
                    //'last' => false, // Stop validation after this rule
                    //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'content' => array(
            'notempty' => array(
                    'rule' => array('notempty'),
                    //'message' => 'Your custom message here',
                    //'allowEmpty' => false,
                    //'required' => false,
                    //'last' => false, // Stop validation after this rule
                    //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'enabled' => array(
            'boolean' => array(
                    'rule' => array('boolean'),
                    //'message' => 'Your custom message here',
                    //'allowEmpty' => false,
                    //'required' => false,
                    //'last' => false, // Stop validation after this rule
                    //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'user_id' => array(
            'numeric' => array(
                    'rule' => array('numeric'),
                    //'message' => 'Your custom message here',
                    //'allowEmpty' => false,
                    //'required' => false,
                    //'last' => false, // Stop validation after this rule
                    //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
    * belongsTo associations
    *
    * @var array
    */
    public $belongsTo = array(
        'User' => array(
                'className' => 'User',
                'foreignKey' => 'user_id',
                'conditions' => '',
                'fields' => '',
                'order' => ''
        )
    );

    /**
    * hasMany associations
    *
    * @var array
    */
    public $hasMany = array(
        'Comment' => array(
                'className' => 'Comment',
                'foreignKey' => 'forum_id',
                'dependent' => false,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'exclusive' => '',
                'finderQuery' => '',
                'counterQuery' => ''
        )
    );

}
