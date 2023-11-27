import { useForm, Head } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import TextInput from '@/Components/TextInput';
import InputLabel from '@/Components/InputLabel';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';

const Edit = ({ auth, barangmasuk, barangs, flash }) => {
    const { data, setData, put, errors, processing } = useForm({
        tanggal_masuk: barangmasuk.tanggal_masuk || '',
        jumlah: barangmasuk.jumlah || '',
        barang_id: barangmasuk.barang_id || '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        put(route('barangmasuk.update', barangmasuk.id));
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <>
                    <h2 className="text-3xl text-gray-500 leading-tight">Barang Masuk</h2>
                    {flash.success && <div className="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-4" role="alert">
                        <p className="font-bold">Success</p>
                        <p>{flash.success}</p>
                    </div>}
                    {flash.error && <div className="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mt-4" role="alert">
                        <p className="font-bold">Error</p>
                        <p>{flash.error}</p>
                    </div>}
                </>
                }
            >

        <Head title="Barang Masuk" />

                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <header>
                <h2 className="text-lg font-medium text-gray-900">Edit Barang Masuk</h2>

                <p className="mt-1 text-sm text-gray-600">
                    Tambah Stok Barang.
                </p>
            </header>
            <form onSubmit={handleSubmit} className="mt-6 space-y-6">
                <div>
                    <InputLabel htmlFor="jumlah" value="Jumlah Pemasukan" />

                    <TextInput
                        id="jumlah"
                        className="mt-1 block w-full"
                        value={data.jumlah}
                        onChange={(e) => setData('jumlah', e.target.value)}
                        isFocused
                    />

                    <InputError className="mt-2" message={errors.jumlah} />
                </div>

                <div className='flex gap-12 items-center justify-start'>
                    <div>
                        <InputLabel htmlFor="barang" value="Barang" />
                        <select
                            disabled
                            value={data.barang_id}
                            onChange={(e) => setData('barang_id', e.target.value)}
                        >
                            <option value='' disabled>Pilih Barang</option>
                            {barangs.map((barang) => (  
                                <option key={barang.slug} value={barang.id}>
                                    [{barang.merk}] {barang.seri}
                                </option>
                            ))}
                        </select>
                        <InputError message={errors.kategori_id} className="mt-2" />
                    </div>

                    <div>
                        <InputLabel htmlFor="tanggal_masuk" value="Tanggal Masuk (hari ini)" />
                        <input
                            type="date"
                            id="tanggal_masuk"
                            className='mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50'
                            value={data.tanggal_masuk}
                            onChange={(e) => setData('tanggal_masuk', e.target.value)}
                            readOnly
                        />
                        <InputError message={errors.tanggal_masuk} className="mt-2" />
                    </div>
                </div>

                    <PrimaryButton className='mt-4 rounded-lg w-full' disabled={processing}>Simpan</PrimaryButton>
                </form>
            </div>
        </div>
        </AuthenticatedLayout>
    );
};

export default Edit;
