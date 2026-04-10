<?php

namespace App\Livewire;

use App\Rules\SafeUrl;
use App\Services\FileUploadService;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrganizationEditor extends Component
{
    use WithFileUploads;

    public string  $name               = '';
    public string  $description        = '';
    public string  $website_url        = '';
    public string  $country            = '';
    public string  $region             = '';
    public ?int    $founding_year      = null;
    public ?int    $students_supported = null;
    public         $logo               = null;
    public bool    $saved              = false;

    public function mount(): void
    {
        $org = auth()->user()->primaryOrganization();
        if (!$org) return;

        $this->name               = $org->name;
        $this->description        = $org->description        ?? '';
        $this->website_url        = $org->website_url        ?? '';
        $this->country            = $org->country            ?? '';
        $this->region             = $org->region             ?? '';
        $this->founding_year      = $org->founding_year;
        $this->students_supported = $org->students_supported;
    }

    public function save(FileUploadService $uploader): void
    {
        $user = auth()->user();
        $org  = $user->primaryOrganization();

        if (!$org) {
            $this->js("window.toast('error', 'No organization found.')");
            return;
        }

        $pivot = $user->organizations()->where('organization_id', $org->id)->first()?->pivot;
        if (!$pivot || (!$pivot->is_primary && $pivot->role !== 'admin' && !$user->isAdmin())) {
            $this->js("window.toast('error', 'You do not have permission to edit this organization.')");
            return;
        }

        $validated = $this->validate([
            'name'               => ['required', 'string', 'max:255'],
            'description'        => ['nullable', 'string', 'max:2000'],
            'website_url'        => ['nullable', 'url', 'max:500', new SafeUrl()],
            'country'            => ['nullable', 'string', 'max:100'],
            'region'             => ['nullable', 'string', 'max:100'],
            'founding_year'      => ['nullable', 'integer', 'min:1800', 'max:' . date('Y')],
            'students_supported' => ['nullable', 'integer', 'min:0'],
            'logo'               => ['nullable', 'file', 'max:5120'],
        ]);

        if ($this->logo) {
            try {
                $old = $org->logo_path;
                $validated['logo_path'] = $uploader->storeImage($this->logo, 'logos', 'uploads');
                unset($validated['logo']);
                if ($old) $uploader->delete($old, 'uploads');
                $this->logo = null;
            } catch (ValidationException $e) {
                $this->addError('logo', collect($e->errors())->first()[0] ?? 'Upload failed.');
                return;
            }
        } else {
            unset($validated['logo']);
        }

        $org->update($validated);

        $listing = $org->directoryListing ?? $org->directoryListing()->create(['user_id' => $user->id]);
        $listing->update(['listing_title' => $org->name, 'last_updated_at' => now()]);

        $this->saved = true;
        $this->js("window.toast('success', 'Organization updated successfully.', 'Saved')");
    }

    public function render()
    {
        $org         = auth()->user()->primaryOrganization();
        $teamMembers = $org?->members()->get() ?? collect();
        return view('livewire.organization-editor', compact('org', 'teamMembers'));
    }
}
