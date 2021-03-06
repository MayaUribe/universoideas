<?php
App::uses('AppController', 'Controller');

/**
 * Comments Controller
 *
 * @property Comment $Comment
 */
class CommentsController extends AppController {
    
    public function beforeFilter() {
        parent::beforeFilter();
        $user = $this->Auth->user();
        
        if(!empty($user)) {
            if($user['id'] === '1')
                $this->Auth->allow(array('index', 'view', 'add', 'edit', 'delete'));
            else {
                $this->Auth->allow(array('view', 'add'));
                $this->Auth->deny(array('index'));
            }
        } else {
            $this->Auth->deny(array('index', 'add', 'edit', 'delete'));
        }
    }
    
    /**
    * index method
    *
    * @return void
    */
    public function index() {
        $this->loadModel('Forum');
        $user = $this->Auth->user();
        $forum_id = $this->params['url']['forum_id'];
        
        $this->paginate = array(
                        'fields' => array('Comment.id', 'Comment.description', 'Comment.forum_id', 'Comment.user_id', 'Comment.created', 'Comment.modified',
                                          'User.id', 'User.username', 'User.name', 'Forum.id','Forum.title'),
                        'conditions' => array('Comment.forum_id' => $forum_id),
                        'order' => array('Comment.modified' => 'desc')
                        );
        unset($this->Forum->virtualFields['count']);
        unset($this->Forum->virtualFields['max_comment']);
        $comments = $this->paginate('Comment');
        $options = array('conditions' => array('Forum.id' => $forum_id));
        $forum = $this->Forum->find('first', $options);
        $this->set(compact('comments', 'user', 'forum_id', 'forum'));
    } 

    /**
    * view method
    *
    * @throws NotFoundException
    * @param string $id
    * @return void
    */
    public function view($id = null) {
        $this->loadModel('User');
        if (!$this->Comment->exists($id)) {
            throw new NotFoundException(__('Invalid comment'));
        }
        $options = array('conditions' => array('Comment.' . $this->Comment->primaryKey => $id));
        $this->set('comment', $this->Comment->find('first', $options));
    }

    /**
    * add method
    *
    * @return void
    */
    public function add() {
        $this->loadModel('Forum');
        $this->layout = 'page';
        $this->set(compact('users', 'user'));
        $user = $this->Auth->user();
        
        if ($this->request->is('post')) {
            $this->Comment->create();
            $this->request->data['Comment']['user_id'] = $user['id'];
            $forum_id = $this->params['url']['forum_id'];
            $this->request->data['Comment']['forum_id'] = $forum_id;
            
            $options = array('conditions' => array('Forum.' . $this->Forum->primaryKey => $forum_id),
                             'fields' => array('Forum.id', 'Forum.title', 'Forum.content', 'Forum.enabled', 'Forum.user_id', 'Forum.created', 'Forum.modified',
                                               'User.id', 'User.username', 'User.name', 'User.lastname', 'User.mail', 'User.role_id'),
                             );
            $forum = $this->Forum->find('first', $options);
            $user_id = $forum['Forum']['user_id'];
            
            if ($this->Comment->save($this->request->data)) {
                $this->Session->setFlash('Tu comentario fue enviado exitosamente.', 'flash_success');
                $this->publishForum($forum_id);
                $this->publishForums($user_id);
                $this->redirect(array('controller' => 'forums', 'action' => 'view/' . $forum_id));
            } else {
                $this->Session->setFlash('Tu comentario no pudo ser enviado. Intente de nuevo.', 'flash_error');
            }
        }
        $forums = $this->Comment->Forum->find('list');
        $users = $this->Comment->User->find('list');
        $this->set(compact('forums', 'users', 'user'));
    }

    /**
    * edit method
    *
    * @throws NotFoundException
    * @param string $id
    * @return void
    */
    public function edit($id = null) {
        $this->loadModel('Forum');
        $user = $this->Auth->user();
        
        if (!$this->Comment->exists($id)) {
            throw new NotFoundException(__('Invalid comment'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $forum_id = $this->params['url']['forum_id'];
            
            $options = array('conditions' => array('Forum.' . $this->Forum->primaryKey => $forum_id),
                             'fields' => array('Forum.id', 'Forum.title', 'Forum.content', 'Forum.enabled', 'Forum.user_id', 'Forum.created', 'Forum.modified',
                                               'User.id', 'User.username', 'User.name', 'User.lastname', 'User.mail', 'User.role_id'),
                             );
            $forum = $this->Forum->find('first', $options);
            $user_id = $forum['Forum']['user_id'];
            
            if ($this->Comment->save($this->request->data)) {
                $this->Session->setFlash('El comentario fue guardado exitosamente.', 'flash_success');
                // buscar el id del foro al que pertenece el comentario $id
                $this->publishForum($forum_id);
                $this->publishForums($user_id);
                $this->redirect(array('action' => 'index?forum_id=' . $forum_id));
            } else {
                $this->Session->setFlash('El comentario no pudo ser guardado. Intente de nuevo.', 'flash_error');
            }
        } else {
            $options = array('conditions' => array('Comment.' . $this->Comment->primaryKey => $id));
            $this->request->data = $this->Comment->find('first', $options);
            
        }
        $forums = $this->Comment->Forum->find('list');
        $users = $this->Comment->User->find('list');
        $comment = $this->Comment->find('first', $options);
        $this->set(compact('forums', 'users', 'comment'));
    }

    /**
    * delete method
    *
    * @throws NotFoundException
    * @param string $id
    * @return void
    */
    public function delete($id = null) {
        $this->loadModel('Forum');
        
        $this->Comment->id = $id;
        if (!$this->Comment->exists()) {
            throw new NotFoundException(__('Invalid comment'));
        }
        $this->request->onlyAllow('post', 'delete');
        $options = array('conditions' => array('Comment.' . $this->Comment->primaryKey => $id));
        $comment = $this->Comment->find('first', $options);
        $forum_id = $comment['Comment']['forum_id'];
        
        $options = array('conditions' => array('Forum.' . $this->Forum->primaryKey => $forum_id),
                             'fields' => array('Forum.id', 'Forum.title', 'Forum.content', 'Forum.enabled', 'Forum.user_id', 'Forum.created', 'Forum.modified',
                                               'User.id', 'User.username', 'User.name', 'User.lastname', 'User.mail', 'User.role_id'),
                        );
        $forum = $this->Forum->find('first', $options);
        $user_id = $forum['Forum']['user_id'];
        
        if ($this->Comment->delete()) {
            $this->Session->setFlash('El comentario fue eliminado.', 'flash_success');
            $this->publishForum($forum_id);
            $this->publishForums($user_id);
            $this->redirect(array('action' => 'index?forum_id=' . $forum_id));
        }
        $this->Session->setFlash('El comentario no pudo ser eliminado. Intente de nuevo.', 'flash_error');
        $this->redirect(array('action' => 'index'));
    }
    
    public function writeFile($data, $file_name) {
        $file = WWW_ROOT . 'includes/published/' . $file_name . '.htm';
        $handle = fopen($file, 'w') or die('Cannot open file:  '.$file);
        
        fwrite($handle, $data);
    }
    
    public function publishView($view, $file_name) {
        $result = $this->requestAction($view, array('return')); 
        
        $this->writeFile($result, $file_name);
    }
    
    public function publishForum($id) {
        $this->publishView("/forums/forum_detail/" . $id, "forums/detail/forum-" . $id);
    }
    
    public function publishForums($user_id) {
        /* PUBLICAR TEMAS DEL FORO */
        $this->publishView("/pages/forums_table", "forums_table");
        $this->publishView("/pages/list_all_table/" . $user_id, "forums/rios/list_all_table_". $user_id);
    }
}
