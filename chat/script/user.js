function search_validate1()
{
    const searchField1 = document.getElementById('search_value_1');
    if (searchField1.value.trim() === '') 
    {
        alert('Cannot search empty value');
        return false;
    }

    return true;
}

function search_validate2()
{
    const searchField2 = document.getElementById('search_value_2');
    if (searchField2.value.trim() === '') 
    {
        alert('Cannot search empty value');
        return false;
    }

    return true;
}