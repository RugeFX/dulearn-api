import React from "react";
import axios from "axios";
import MaterialBox from "./MaterialBox";

const { useEffect, useState } = React;

const MainHome = (props) => {
    const [materials, setMaterials] = useState([]);

    useEffect(() => {
        axios
            .get("/api/materials")
            .then((res) => {
                console.log(res.data);
                setMaterials(res.data.data);
            })
            .catch((err) => {
                console.error(err);
            });
    }, []);
    return (
        <main className="h-screen bg-[#070B30] mt-20">
            <div className="text-white">
                {materials.map((mat) => (
                    <MaterialBox
                        key={mat.id}
                        title={mat.title}
                        desc={mat.material}
                    />
                ))}
            </div>
        </main>
    );
};

export default MainHome;
