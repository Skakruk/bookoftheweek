<?PHP

// Stick your DBOjbect subclasses in here (to help keep things tidy).

class User extends DBObject {
    public function __construct($id = null) {
        parent::__construct('users', array('nid', 'username', 'password', 'level', 'bibl_name', 'bibl_from', 'bibl_name_pl', 'country', 'position', 'logo', 'link', 'local_title'), $id);
    }

    public function setPassword($password) {
        $Config = Config::getConfig();

        if ($Config->useHashedPasswords === true)
            $this->password = sha1($password . $Config->authSalt);
        else
            $this->password = $password;
    }
}

class Photo extends DBObject {
    private $fullpath;

    public function __construct($id = null) {
        parent::__construct('biblio_photos', array('filename', 'title', 'description', 'created_by', 'week', 'likes', 'author'), $id);
        $this->fullpath = 'upload_photos/' . $this->filename;
        if (!empty($this->filename)) {
            require_once 'ThumbLib.inc.php';
            $this->thumb = PhpThumbFactory::create($this->fullpath);
        }
    }

    public function adaptiveResize($size) {

        if (!empty($this->filename)) {
            require_once 'ThumbLib.inc.php';
            $thumb = PhpThumbFactory::create($this->fullpath);
        }
        $savepath = 'upload_photos/.' . $size;
        $size = explode('x', $size);
        $thumb->adaptiveResize($size[0], $size[1]);
        if (!is_dir($savepath))
            mkdir($savepath, 0777);

        $thumb->save($savepath . '/' . $this->filename);
    }

    public function resize($size) {

        if (!empty($this->filename)) {
            require_once 'ThumbLib.inc.php';
            $thumb = PhpThumbFactory::create($this->fullpath);
        }
        $savepath = 'upload_photos/.' . $size;
        $size = explode('x', $size);
        $thumb->resize($size[0], $size[1]);
        if (!is_dir($savepath))
            mkdir($savepath, 0777);
        $thumb->save($savepath . '/' . $this->filename);
    }

    public function previewResize($width) {
        if (!empty($this->filename)) {
            require_once 'ThumbLib.inc.php';
            $thumb = PhpThumbFactory::create($this->fullpath);
        }
        $savePath = 'upload_photos/.' . $width;
        $thumb->resize($width);
        if (!is_dir($savePath))
            mkdir($savePath, 0777);
        $thumb->save($savePath . '/' . $this->filename);
    }

    public function upload($tmpname) {
        $this->fullpath = 'upload_photos/' . $this->filename;
        move_uploaded_file($tmpname, $this->fullpath);
        $this->previewResize(300);
        $this->adaptiveResize('300x225');
        $this->resize('600x600');
    }
}