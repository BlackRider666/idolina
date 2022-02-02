<?php


namespace App\Repo;

use App\Models\Conference\Conference;
use App\Models\User\User;
use BlackParadise\LaravelAdmin\Core\CoreRepo;
use BlackParadise\LaravelAdmin\Core\StorageManager;
use Exception;
use Highlight\Mode;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class ConferenceRepo extends CoreRepo
{
    protected function getModelClass(): string
    {
        return Conference::class;
    }

    /**
     * @param array $data
     * @return LengthAwarePaginator
     */
    public function search(array $data): LengthAwarePaginator
    {
        $perPage = array_key_exists('perPage',$data)?$data['perPage']:10;
        $sortBy = $data['sortBy'] ?: 'title';
        $sortDesc = array_key_exists('sortDesc',$data)?$data['sortDesc']:true;
        $query = $this->query();

        if (array_key_exists('title',$data)) {
            $query->where('title','like','%'.$data['title'].'%');
        }
        if (array_key_exists('city_id',$data)) {
            $query->where('city_id', $data['city_id']);
        }
        if (array_key_exists('user_id',$data)) {
            $query->where('user_id', $data['user_id']);
        }

        return $query->with(['organizers'])->orderBy($sortBy,$sortDesc?'desc':'asc')->paginate($perPage);
    }

    /**
     * @param array $data
     * @param null $file
     * @return Builder|Model
     */
    public function create(array $data, $file = null)
    {
        if ($file) {
            $storage = new StorageManager();
            $data['file'] = $storage
                ->saveFile($file,Conference::PDF_FILE_PATH);
        }
        if (!$conference = $this->query()->create($data)) {
            throw new RuntimeException('Error on creating conference!',500);
        }
        if (!$conference->organizers()->sync($data['organizers'])) {
            throw new RuntimeException('Error on assign organizers to conference!',500);
        }
        $conference = $this->findWith($conference->getKey(),['organizers']);

        return $conference;
    }

    /**
     * @param int $id
     * @param array $data
     * @param null $file
     * @return Builder|Builder[]|Collection|Model|Model[]|null
     */
    public function update(int $id, array $data, $file = null)
    {

        if (!$conference = $this->query()->find($id)) {
            throw new RuntimeException('Conference not found!',404);
        }
        if ($file) {
            $storage = new StorageManager();
            if($conference->file !== null) {
                $storage->deleteFile($conference->file,Conference::PDF_FILE_PATH);
            }
            $data['file'] = $storage
                ->saveFile($file,Conference::PDF_FILE_PATH);
        }
        if (!$conference->update($data)) {
            throw new RuntimeException('Error on updating conference!',500);
        }
        Log::alert($data);
        if (!$conference->organizers()->sync($data['organizers'])) {
            throw new RuntimeException('Error on assign organizers to conference!',500);
        }

        $conference = $this->findWithAll($id);

        return $conference;
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function delete(int $id): void
    {
        if (!$conference = $this->query()->find($id)) {
            throw new RuntimeException('Conference not found!',404);
        }
        if (!$conference->delete()) {
            throw new RuntimeException('Error on destroying conference!',500);
        }
    }

    /**
     * @param int $id
     * @param array $with
     * @return Model|null
     */
    public function findWith(int $id, array $with): ?Model
    {
        return $this->query()->with($with)->find($id);
    }

    public function findWithAll(int $id)
    {
        return $this->query()
                    ->with(['city','country','organizers','articles','articles.category', 'orgCommittee', 'editors'])
                    ->find($id);
    }

    /**
     * @param int $id
     * @param array $articleData
     * @return Model|null
     */
    public function addArticle(int $id, array $articleData) : ?Model
    {
        if (!$conference = $this->query()->find($id)) {
            throw new RuntimeException('Conference not found!',404);
        }
        $article = (new ArticleRepo())->create($articleData);
        if (!$conference->articles()->sync($article,false)) {
            throw new RuntimeException('Error on attach article to conference!',500);
        }
        $conference = $this->findWithAll($id);

        return $conference;
    }
}
