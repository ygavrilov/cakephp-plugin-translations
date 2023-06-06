<?php
declare(strict_types=1);

namespace Translations\Controller\Admin;

use Translations\Controller\AppController;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

class TranslationsController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $user = $this->request->getAttribute('identity');
        if (!$user->can('access', $this->request)) {
            throw new ForbiddenException(__('No access'));
        }
        $this->viewBuilder()->setLayout($user->user_layout);
        $this->path_to_folders = ROOT . DS . 'resources' . DS . 'locales' . DS;
    }

    public function index()
    {
        $this->loadModel('Translations');
        $translations = $this->Translations
            ->find('all')
            ->order(['en' => 'ASC'])
            ->toArray();
        $this->set(compact('translations'));
    }

    public function importFiles()
    {
        $this->loadModel('Translations');
        // delete all translations
        $this->Translations->deleteAll(['1 = 1']);
        //  get the files
        //  path to folders
        

        //  get the folders 
        $folders = new Folder($this->path_to_folders);
        $folders = $folders->read(true, true, true)[0];

        //  get the files
        $files = [];
        foreach ($folders as $folder) {
            $locale = substr($folder, strrpos($folder, $this->path_to_folders) + strlen($this->path_to_folders));
            $files[$locale] = new Folder($folder);
            $files[$locale] = $files[$locale]->findRecursive('.*\.po');
        }

        //  read each file
        $translations = [];
        foreach ($files as $locale => $file) 
        {
            $locale = str_replace('"', '', $locale);
            foreach ($file as $f) 
            {
                $file = new File($f);
                $content = $file->read();
                $content = explode("\n", $content);

                $content = array_filter($content, function ($line) {
                    return strpos($line, '#') !== 0;
                });
                
                foreach($content as $index => $line)
                {
                    if (strpos($line, 'msgid') === false) {
                        continue;
                    }
                    $line = explode('msgid', $line);
                    try {
                        $translations[$locale][str_replace('"', '', trim($line[1]))] = str_replace('"', '', trim(explode('msgstr', $content[$index + 1])[1]));
                    } catch (\Throwable $th) {
                        debug($th->getMessage());
                        debug($file);
                        debug($content[$index + 1]);
                    }
                }
            }
        }

        //  create new array where the key is the locale and the value is the array of translations
        $new_translations = [];
        foreach ($translations as $locale => $translation) 
        {
            foreach ($translation as $key => $value) 
            {
                if (array_key_exists($key, $new_translations) === false) {
                    $new_translations[$key] = [
                        $locale => $value
                    ];
                } else {
                    $new_translations[$key][$locale] = $value;
                }
                
            }
        }

        $translation_entities = [];
        //  loop new_translations and add new entities to translations entities where key is the en, and es and ru are the values
        foreach ($new_translations as $key => $value) 
        {
            $translation_entities[$key] = $this->Translations->newEmptyEntity();

            $translation_entities[$key]->en = $key;

            //  if 'es' key exists add it to entity
            if (array_key_exists('es', $value)) {
                $translation_entities[$key]->es = $value['es'];
            }
            //  same for 'ru'
            if (array_key_exists('ru', $value)) {
                $translation_entities[$key]->ru = $value['ru'];
            }
        }

        //  save all entities
        foreach ($translation_entities as $translation_entity) 
        {
            $this->Translations->save($translation_entity);
        }

        $this->Flash->success(__('Translations imported'));
        return $this->redirect(['action' => 'index']);

    }

    public function generate($locale = 'es')
    {
        //  check if folder exists
        if (!is_dir($this->path_to_folders . $locale)) {
            mkdir($this->path_to_folders . $locale);
        }

        //  create file
        $file = new File($this->path_to_folders . $locale . DS . 'default.po', true);

        //  get all translations
        $this->loadModel('Translations');
        $translations = $this->Translations
            ->find('all')
            ->order(['en' => 'ASC'])
            ->toArray();

        //  loop translations and write to file
        foreach ($translations as $translation) 
        {
            $file->write('msgid "' . $translation->en . '"' . "\n");
            $file->write('msgstr "' . $translation->$locale . '"' . "\n");
        }
        
        $this->Flash->success(__('File generated'));
        return $this->redirect(['action' => 'index']);

    }

    public function edit($id)
    {
        $this->loadModel('Translations');
        $translation = $this->Translations->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) 
        {
            $translation = $this->Translations->patchEntity($translation, $this->request->getData());
            if ($this->Translations->save($translation)) {
                $this->Flash->success(__('The translation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The translation could not be saved. Please, try again.'));
        }
        $this->set(compact('translation'));
    }

    public function add()
    {
        $this->loadModel('Translations');
        $translation = $this->Translations->newEmptyEntity();
        if ($this->request->is('post')) 
        {
            $translation = $this->Translations->patchEntity($translation, $this->request->getData());
            if ($this->Translations->save($translation)) {
                $this->Flash->success(__('The translation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The translation could not be saved. Please, try again.'));
        }
        $this->set(compact('translation'));
    }

    public function delete($id)
    {
        $this->loadModel('Translations');
        $this->request->allowMethod(['post', 'delete']);
        $translation = $this->Translations->get($id);
        if ($this->Translations->delete($translation)) {
            $this->Flash->success(__('The translation has been deleted.'));
        } else {
            $this->Flash->error(__('The translation could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
