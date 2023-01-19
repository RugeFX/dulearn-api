const dataUser = document.getElementById("datauser");

fetchUsers().then((data) => {
    console.log(data);
    data.data.forEach((user) => {
        console.log("masuk foreach");
        const userEl = document.createElement("p");
        userEl.innerHTML = `${user.reg_num} - ${user.registered_user.name}`;
        userEl.setAttribute("id", user.id);
        dataUser.append(userEl);
    });
    console.log("kelar");
});

async function fetchUsers() {
    const res = await fetch("api/users");
    const data = await res.json();

    return data;
}
