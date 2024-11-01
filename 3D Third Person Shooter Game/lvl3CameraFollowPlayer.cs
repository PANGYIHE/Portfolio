using UnityEngine;

public class CameraFollowPlayer : MonoBehaviour
{
    float rotationX = 0f;
    float rotationY = 0f;

    public float sensitivity = 3f;

    void Update()
    {
        if (!PauseMenu.Pausing)
        {
            rotationY += Input.GetAxis("Mouse X") * sensitivity;
            rotationX += Input.GetAxis("Mouse Y") * -1 * sensitivity;
            transform.localEulerAngles = new Vector3(rotationX, rotationY, 0);
        }
    }
}