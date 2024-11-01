using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.SceneManagement;
public class PauseMenu : MonoBehaviour
{
    public string levelrestart, levelSelect;

    public GameObject pauseScreen, startScreen;
    public static bool Pausing;
    void Start()
    {
        Pausing = true;
        startScreen.SetActive(true);
        Time.timeScale = 0;
        PlayerController.isShooting = false;
    }
    // Update is called once per frame
    void Update()
    {
        if (Input.GetKeyDown(KeyCode.Escape))
        {
            PauseUnpause();
        }
    }
    public void PauseUnpause()
    {
        if (Pausing)
        {
            Pausing = false;
            pauseScreen.SetActive(false);
            Time.timeScale = 1;
        }
        else if (!Pausing)
        {
            Pausing = true;
            pauseScreen.SetActive(true);
            Time.timeScale = 0;
        }
    }

    public void StartGame()
    {
        Pausing = false;
        startScreen.SetActive(false);
        Time.timeScale = 1f;
    }

    public void LevelSelect()
    {
        PlayerPrefs.SetString("CurrentLevel", SceneManager.GetActiveScene().name);
        SceneManager.LoadScene(levelSelect);
        Time.timeScale = 1f;
    }

    public void LevelRestart()
    {
        SceneManager.LoadScene(levelrestart);
        Time.timeScale = 1;
    }
}