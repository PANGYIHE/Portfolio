using UnityEngine;
using UnityEngine.SceneManagement;

public class LoadScene : MonoBehaviour
{
    public string sceneNameToLoad;

    public void LoadSpecifiedScene()
    {
        SceneManager.LoadScene(sceneNameToLoad);
    }
}
