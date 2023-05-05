import { Link } from "@inertiajs/react"

const SideLink = ({ active = false, className = '', children, ...props }) => {
  return (
    <div>
    <Link
    {...props}
    className={
        'flex items-center text-md space-x-1 font-bold leading-5 ps-4 transition duration-150 py-3 ease-in-out focus:outline-none border-y border-transparent ' +
        (active
            ? 'border-indigo-400 text-gray-900 bg-gray-200 focus:border-indigo-700'
            : 'border-transparent text-gray-200 hover:bg-white/25 hover:border-gray-300 focus:text-white focus:border-gray-300 focus:bg-white/25') +
        className
    }
>
    {children}
</Link>
</div>
  )
}

export default SideLink