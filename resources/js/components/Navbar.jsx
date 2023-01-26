import logo from "./../../../public/img/logo.png";

const Navbar = () => {
    return (
        <nav className="bg-[#161719] w-screen fixed top-0 p-3">
            <img src={logo}></img>
        </nav>
    );
};

export default Navbar;
