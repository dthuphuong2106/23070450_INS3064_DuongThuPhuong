
<?php

namespace App\Controllers;

use App\Models\ContactModel;

class ContactController
{
    private ContactModel $contactModel;

    public function __construct()
    {
        $this->contactModel = new ContactModel();
    }

    /**
     * Display all contacts.
     */
    public function index(): void
    {
        $search = trim($_GET['search'] ?? '');

        $contacts = $this->contactModel->getAll($search);

        $this->render('contacts/index', [
            'contacts' => $contacts,
            'search' => $search
        ]);
    }

    /**
     * Display the create contact form.
     */
    public function create(): void
    {
        $this->render('contacts/create', [
            'errors' => [],
            'old' => []
        ]);
    }

    /**
     * Process new contact submission.
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/create');
        }

        $data = $this->getFormData();

        $errors = $this->validateContact($data);

        if (empty($errors) &&
            $this->contactModel->emailExists($data['email'])) {
            $errors['email'] = 'This email already exists.';
        }

        if (!empty($errors)) {
            $this->render('contacts/create', [
                'errors' => $errors,
                'old' => $data
            ]);
            return;
        }

        $this->contactModel->create($data);

        $_SESSION['success'] = 'Contact created successfully!';

        $this->redirect('/');
    }

    /**
     * Display the edit contact form.
     */
    public function edit(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            $this->notFound();
            return;
        }

        $contact = $this->contactModel->getById($id);

        if (!$contact) {
            $this->notFound();
            return;
        }

        $this->render('contacts/edit', [
            'contact' => $contact,
            'errors' => [],
            'old' => $contact
        ]);
    }

    /**
     * Process contact update.
     */
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            $this->notFound();
            return;
        }

        $contact = $this->contactModel->getById($id);

        if (!$contact) {
            $this->notFound();
            return;
        }

        $data = $this->getFormData();

        $errors = $this->validateContact($data);

        if (empty($errors) &&
            $this->contactModel->emailExists($data['email'], $id)) {
            $errors['email'] = 'This email already exists.';
        }

        if (!empty($errors)) {
            $this->render('contacts/edit', [
                'contact' => $contact,
                'errors' => $errors,
                'old' => array_merge($contact, $data)
            ]);
            return;
        }

        $this->contactModel->update($id, $data);

        $_SESSION['success'] = 'Contact updated successfully!';

        $this->redirect('/');
    }

    /**
     * Delete a contact.
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            $this->notFound();
            return;
        }

        $contact = $this->contactModel->getById($id);

        if (!$contact) {
            $this->notFound();
            return;
        }

        $this->contactModel->delete($id);

        $_SESSION['success'] = 'Contact deleted successfully!';

        $this->redirect('/');
    }

    /**
     * Get and clean form data.
     */
    private function getFormData(): array
    {
        return [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'group_name' => trim($_POST['group_name'] ?? ''),
            'notes' => trim($_POST['notes'] ?? '')
        ];
    }

    /**
     * Validate contact information.
     */
    private function validateContact(array $data): array
    {
        $errors = [];

        if ($data['name'] === '') {
            $errors['name'] = 'Name is required.';
        }

        if ($data['email'] === '') {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        if ($data['phone'] === '') {
            $errors['phone'] = 'Phone number is required.';
        }

        return $errors;
    }

    /**
     * Load a view inside the shared layout.
     */
    private function render(string $view, array $data = []): void
    {
        extract($data);

        $viewFile = dirname(__DIR__) . '/views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException('View not found: ' . $view);
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require dirname(__DIR__) . '/views/layout.php';
    }

    /**
     * Redirect to another route.
     */
    private function redirect(string $route): void
    {
        header('Location: ?route=' . urlencode($route));
        exit;
    }

    /**
     * Display a 404 response.
     */
    private function notFound(): void
    {
        http_response_code(404);
        echo '<h1>404 - Contact not found</h1>';
        echo '<p><a href="?route=%2F">Back to contacts</a></p>';
    }
}
