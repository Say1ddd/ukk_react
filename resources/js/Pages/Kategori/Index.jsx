const Index = ({ auth, kategoris }) => {
  return (
    <AuthenticatedLayout
    user={auth.user}
    header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Kategori</h2>}
>
    <Head title="Kategori" />

        <div className="py-12">
            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    {kategoris.map((kategori, index) => (
                        <div key={index} className="p-6 bg-white border-b border-gray-200">
                            {kategori.nama}
                        </div>
                    ))}
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
  )
}

export default Index