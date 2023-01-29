import axios from "axios";
import logo from "./../../../public/img/logo.png";

const Navbar = () => {
    return (
        <nav className="bg-[#161719] w-screen fixed top-0 p-3 h-20 flex gap-5 items-center">
            <img src={logo}></img>
            <button
                className="text-sm grid place-items-center h-3/4 font-bold text-[#1c215c] bg-[#FAA41A] hover:bg-[#b4740c] rounded-lg px-6 focus:outline-0 focus:shadow-input focus:shadow-white transition-all"
                onClick={() => {
                    axios
                        .get("/logout")
                        .then((res) => {
                            if (res.statusText === "OK") {
                                window.location.href = "/login";
                            }
                        })
                        .catch((err) => console.error(err));
                }}
            >
                Log Out
            </button>
        </nav>
    );
};

export default Navbar;
