<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Models\FilmManager;
use Nette\Application\UI\Form;

final class FilmsPresenter extends Nette\Application\UI\Presenter
{
    private FilmManager $filmManager;

	public function __construct(FilmManager $filmManager)
	{
		$this->filmManager = $filmManager;
	}

    public function startup()
    {
        parent::startup();

        if (!$this->getUser()->isLoggedIn() && $this->getAction() !== 'signIn')
        {
            $this->redirect('Homepage:signIn');
            $this->terminate();
        }
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

    	$form->addSubmit('send', 'Add new film');
    	$form->onSuccess[] = [$this, 'filmFormSucceeded'];

    	return $form;
    }

	public function filmFormSucceeded(array $data): void
    {
        $this->filmManager->saveFilm($data);
        $this->flashMessage('New film was added', 'success');

		if ($this->isAjax()) {
            $this->redrawControl('ajaxChange');
            $this->redrawControl('flashMessages');
        }
    }

    public function renderDefault(): void
    {
		$this->template->films = $this->filmManager->getFilms();
    }
}
