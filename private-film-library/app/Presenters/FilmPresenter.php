<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Models\FilmManager;
use Nette;
use Nette\Application\UI\Form;


final class FilmPresenter extends Nette\Application\UI\Presenter
{
    private FilmManager $filmManager;
    private CreatorManager $creatorManager;

	public function __construct(FilmManager $filmManager)
	{
		$this->filmManager = $filmManager;
	}

    protected function createComponentFilmForm(): Form
    {
        $genres = $this->filmManager->getGenres();

    	$form = new Form;
        $form->getElementPrototype()->class('ajax');
    	$form->addText('title', 'Title:')
    		->setRequired();
    	$form->addInteger('length', 'Length:')
    		->setRequired();
        $form->addText('landscape', 'Landscape:')
    		->setRequired();
        $form->addSelect('genre_id', 'Genre:', $genres)
            ->setRequired()
            ->setPrompt('Pick a genre');
        $form->addText('country', 'Country:')
    		->setRequired();

    	$form->addSubmit('send', 'Save and publish');
    	$form->onSuccess[] = [$this, 'filmFormSucceeded'];

    	return $form;
    }

    public function filmFormSucceeded(array $data): void
    {
        $filmId = intval($this->getParameter('filmId'));

        $this->filmManager->updateFilm($data, $filmId);
        $this->flashMessage('Film was updated successfully', 'success');

        if ($this->isAjax()) {
            $this->redrawControl('ajaxChange');
            $this->redrawControl('flashMessages');
        }
    }

    public function renderShow(int $filmId): void
    {
        $film = $this->filmManager->getFilm($filmId);
        $creators = $this->filmManager->getCreatorsListNot($filmId);
        $creatorsList = $this->filmManager->getCreatorsList($filmId);
        
        if (!$film) {
            $this->error('Page not found!');
        }

        $this->getComponent('filmForm')->setDefaults($film);

        $this->template->creators = $creators;
        $this->template->creatorsList = $creatorsList;
        $this->template->film = $film;
    }

    public function renderEdit(int $filmId): void
    {
        $film = $this->filmManager->getFilm($filmId);
        
        if (!$film) {
            $this->error('Page not found!');
        }

        $this->getComponent('filmForm')->setDefaults($film);
    }

    public function actionDelete(int $filmId): void
    {
        $this->filmManager->deleteFilm($filmId);

        $this->flashMessage('Film was deleted', 'success');
    	$this->redirect('Films:default');
    }

    public function handleAddCreator(int $filmId, int $creatorId): void
    {        
        $this->filmManager->addCreator($filmId, $creatorId);

        if ($this->isAjax()) {
            $this->redrawControl('ajaxListCreatorsAdd');
            $this->redrawControl('ajaxListCreators');
        }
    }

    public function handleRemoveCreator(int $filmId, int $creatorId): void
    {        
        $this->filmManager->removeCreator($filmId, $creatorId);

        if ($this->isAjax()) {
            $this->redrawControl('ajaxListCreatorsAdd');
            $this->redrawControl('ajaxListCreators');
        }
    }
}
